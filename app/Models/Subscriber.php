<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'is_active',
        'download_count',
        'last_download_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_download_at' => 'datetime'
    ];
}