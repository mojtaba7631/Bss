<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterAccess extends Model
{
    use HasFactory;

    protected $table = 'letter_access';
    protected $guarded = [];
}
