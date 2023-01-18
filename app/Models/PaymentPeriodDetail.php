<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPeriodDetail extends Model
{
    use HasFactory;

    protected $table = 'payment_period_detail';
    protected $guarded = [];
}
