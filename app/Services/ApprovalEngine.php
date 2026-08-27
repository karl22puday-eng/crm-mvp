<?php

namespace App\Services;

use App\Models\SizingParameter;
use Illuminate\Support\Facades\Cache;

class ApprovalEngine
{
    protected array $params;

    public function __construct()
    {
        // Cache the 13 parameters for 60 seconds so we don't hit the DB on every calculation call
        $this->params = Cache::remember('sizing_parameters', 60, function () {
            return SizingParameter::pluck('value', 'key')->toArray();
        });
    }

    protected function p(string $key): float
    {
        return (float) $this->params[$key];
    }

    /**
     * Clamp a raw computed amount to [0, cap], and report whether the cap was hit.
     */
    protected function clamp(float $raw, float $cap): array
    {
        $amount = min($raw, $cap);
        $capped = $raw > $cap;

        return ['amount' => round($amount, 2), 'capped' => $capped];
    }

    /**
     * B1 — Revenue model. Returns 4 lanes.
     */
    public function fromRevenue(float $revenue): array
    {
        $floor = $this->p('p_Floor');
        $cap = $this->p('p_Cap');
        $bankCeil = $this->p('p_BankCeil');

        $belowFloor = $revenue < $floor;

        $fintechTL = $this->clamp($revenue * $this->p('p_Rev_FTL'), $cap);
        $fintechLOC = $this->clamp($revenue * $this->p('p_Rev_FLOC'), $cap);
        $bankTL = $this->clamp($revenue * $this->p('p_Rev_BTL'), $bankCeil);
        $bankLOC = $this->clamp($revenue * $this->p('p_Rev_BLOC'), $bankCeil);

        return [
            'source_metric' => 'revenue',
            'below_floor' => $belowFloor,
            'lanes' => [
                'fintech_term_loan' => $this->lane($fintechTL, $cap, $belowFloor, isFintech: true),
                'fintech_loc' => $this->lane($fintechLOC, $cap, $belowFloor, isFintech: true),
                'bank_term_loan' => $this->lane($bankTL, $bankCeil, $belowFloor, isFintech: false),
                'bank_loc' => $this->lane($bankLOC, $bankCeil, $belowFloor, isFintech: false),
            ],
        ];
    }

    /**
     * B2 — Profit model. Bank lanes only, capped at p_Cap (not the bank ceiling —
     * the workbook treats DSCR-derived profit capacity as capped at $250K, not $100K).
     */
    public function fromProfit(float $profit): array
    {
        $floor = $this->p('p_Floor'); // profit model's own floor is $25K in the sheet;
        // for MVP we reuse p_Floor as the single sub-floor signal at the engine level.
        $cap = $this->p('p_Cap');

        $belowFloor = $profit < 25000; // profit model's modeled floor per the workbook

        $bankTL = $this->clamp($profit * $this->p('p_Pro_TL'), $cap);
        $bankLOC = $this->clamp($profit * $this->p('p_Pro_LOC'), $cap);

        return [
            'source_metric' => 'net_profit',
            'below_floor' => $belowFloor,
            'lanes' => [
                'bank_term_loan' => $this->lane($bankTL, $cap, $belowFloor, isFintech: false),
                'bank_loc' => $this->lane($bankLOC, $cap, $belowFloor, isFintech: false),
            ],
        ];
    }

    /**
     * B3 — Deposits model. Returns 4 lanes. Strongest single factor in the set.
     */
    public function fromDeposits(float $deposits): array
    {
        $floor = 10000; // deposits model's own modeled floor per the workbook
        $cap = $this->p('p_Cap');
        $bankCeil = $this->p('p_BankCeil');

        $belowFloor = $deposits < $floor;

        $fintechTL = $this->clamp($deposits * $this->p('p_Dep_FTL'), $cap);
        $fintechLOC = $this->clamp($deposits * $this->p('p_Dep_FLOC'), $cap);
        $bankTL = $this->clamp($deposits * $this->p('p_Dep_BTL'), $bankCeil);
        $bankLOC = $this->clamp($deposits * $this->p('p_Dep_BLOC'), $bankCeil);

        return [
            'source_metric' => 'deposits',
            'below_floor' => $belowFloor,
            'lanes' => [
                'fintech_term_loan' => $this->lane($fintechTL, $cap, $belowFloor, isFintech: true),
                'fintech_loc' => $this->lane($fintechLOC, $cap, $belowFloor, isFintech: true),
                'bank_term_loan' => $this->lane($bankTL, $bankCeil, $belowFloor, isFintech: false),
                'bank_loc' => $this->lane($bankLOC, $bankCeil, $belowFloor, isFintech: false),
            ],
        ];
    }

    /**
     * Builds one lane's result: amount + flag, following the workbook's Flag legend.
     */
    protected function lane(array $clamped, float $ceilingUsed, bool $belowFloor, bool $isFintech): array
    {
        $flag = null;

        if ($belowFloor) {
            $flag = 'Sub-floor';
        } elseif ($clamped['capped']) {
            $flag = $isFintech
                ? 'Cap (Fintech)'
                : 'Ceiling ($100,000 modeled — Wells BusinessLine publishes $150,000)';
        }

        return [
            'amount' => $clamped['amount'],
            'flag' => $flag,
        ];
    }

    /**
     * Section 4 equivalent — governing constraint: the lowest amount entered per lane,
     * across whichever metrics were actually provided. Profit is excluded from fintech lanes.
     */
    public function governingConstraint(array $results): array
    {
        $lanes = ['fintech_term_loan', 'fintech_loc', 'bank_term_loan', 'bank_loc'];
        $governed = [];

        foreach ($lanes as $lane) {
            $candidates = [];

            foreach ($results as $metric => $result) {
                if ($metric === 'net_profit' && str_starts_with($lane, 'fintech')) {
                    continue; // fintech lenders do not price off profit
                }
                if (isset($result['lanes'][$lane])) {
                    $candidates[$metric] = $result['lanes'][$lane]['amount'];
                }
            }

            if (empty($candidates)) {
                continue;
            }

            $governingMetric = array_keys($candidates, min($candidates))[0];

            $governed[$lane] = [
                'amount' => $candidates[$governingMetric],
                'governed_by' => $governingMetric,
            ];
        }

        return $governed;
    }
        /**
     * Runs all entered metrics for an application, saves every lane result to
     * approval_calculations, and returns the governing constraint summary.
     */
    public function calculateForApplication(\App\Models\CrmApplication $application): array
    {
        $profile = $application->financialProfile;

        if (! $profile) {
            throw new \RuntimeException('Application has no financial profile to calculate from.');
        }

        $results = [];

        if (! is_null($profile->annual_revenue)) {
            $results['revenue'] = $this->fromRevenue((float) $profile->annual_revenue);
        }
        if (! is_null($profile->annual_net_profit)) {
            $results['net_profit'] = $this->fromProfit((float) $profile->annual_net_profit);
        }
        if (! is_null($profile->avg_monthly_deposits)) {
            $results['deposits'] = $this->fromDeposits((float) $profile->avg_monthly_deposits);
        }

        if (empty($results)) {
            throw new \RuntimeException('Financial profile has no metrics entered.');
        }

        // Clear any previous calculations for this application before writing fresh ones
        $application->approvalCalculations()->delete();

        foreach ($results as $metric => $result) {
            foreach ($result['lanes'] as $lane => $laneResult) {
                $application->approvalCalculations()->create([
                    'lane' => $lane,
                    'source_metric' => $metric,
                    'amount' => $laneResult['amount'],
                    'flag' => $laneResult['flag'],
                    'ran_at' => now(),
                ]);
            }
        }

        return $this->governingConstraint($results);
    }
}