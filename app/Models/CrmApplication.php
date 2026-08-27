<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmApplication extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = ['supplier_id', 'status'];
}