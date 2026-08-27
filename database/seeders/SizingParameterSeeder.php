<?php

namespace Database\Seeders;

use App\Models\SizingParameter;
use Illuminate\Database\Seeder;

class SizingParameterSeeder extends Seeder
{
    public function run(): void
    {
        $params = [
            ['key' => 'p_Floor',    'value' => 50000,  'applies_to' => 'Band floor — outputs below this are Sub-floor'],
            ['key' => 'p_Cap',      'value' => 250000, 'applies_to' => 'Band cap (all lanes)'],
            ['key' => 'p_BankCeil', 'value' => 100000, 'applies_to' => 'Bank unsecured ceiling (conservative low end of $100K-$150K)'],
            ['key' => 'p_Rev_FTL',  'value' => 0.33,   'applies_to' => 'Annual revenue -> fintech/online term loan'],
            ['key' => 'p_Rev_FLOC', 'value' => 0.30,   'applies_to' => 'Annual revenue -> fintech/online LOC'],
            ['key' => 'p_Rev_BTL',  'value' => 0.25,   'applies_to' => 'Annual revenue -> bank (unsec.) term loan'],
            ['key' => 'p_Rev_BLOC', 'value' => 0.20,   'applies_to' => 'Annual revenue -> bank (unsec.) LOC'],
            ['key' => 'p_Pro_TL',   'value' => 1.5,    'applies_to' => 'Annual net profit -> bank term loan (DSCR-derived)'],
            ['key' => 'p_Pro_LOC',  'value' => 1.0,    'applies_to' => 'Annual net profit -> bank LOC (DSCR-derived)'],
            ['key' => 'p_Dep_FTL',  'value' => 4.0,    'applies_to' => 'Avg monthly deposits -> fintech/online term loan'],
            ['key' => 'p_Dep_FLOC', 'value' => 3.5,    'applies_to' => 'Avg monthly deposits -> fintech/online LOC'],
            ['key' => 'p_Dep_BTL',  'value' => 3.0,    'applies_to' => 'Avg monthly deposits -> bank (unsec.) term loan'],
            ['key' => 'p_Dep_BLOC', 'value' => 2.5,    'applies_to' => 'Avg monthly deposits -> bank (unsec.) LOC'],
        ];

        foreach ($params as $param) {
            SizingParameter::updateOrCreate(['key' => $param['key']], $param);
        }
    }
}