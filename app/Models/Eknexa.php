<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eknexa extends Model
{
    use HasFactory;

    protected $table = 'eknexa';
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'content_file_path',
    ];
}
