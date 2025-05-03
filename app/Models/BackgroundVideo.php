<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundVideo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'path', 'mime_type', 'is_active'];

    /**
     * Dapatkan video background yang aktif
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}