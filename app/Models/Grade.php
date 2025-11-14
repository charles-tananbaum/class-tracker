<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'midterm',
        'homework',
        'final',
    ];

    protected $casts = [
        'midterm' => 'decimal:2',
        'homework' => 'decimal:2',
        'final' => 'decimal:2',
    ];

    public function hbsClass(): BelongsTo
    {
        return $this->belongsTo(HbsClass::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
