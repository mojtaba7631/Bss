<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveTime extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "save_times";
}
