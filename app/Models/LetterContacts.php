<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterContacts extends Model
{
    use HasFactory;

    protected $table = 'letter_contacts';
    protected $guarded = [];
}
