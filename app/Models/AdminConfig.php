<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'data_type', 'owner_editable', 'purpose'];
}