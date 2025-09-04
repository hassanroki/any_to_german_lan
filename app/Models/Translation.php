<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    //
    protected $fillable = ['original_text', 'translated_text', 'chassi', 'german_pdf'];
}
