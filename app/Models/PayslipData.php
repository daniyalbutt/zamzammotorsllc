<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipData extends Model
{
    use HasFactory;

    protected $table = 'payslips_data'; 
    protected $guarded = [];
}
