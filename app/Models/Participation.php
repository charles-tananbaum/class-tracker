<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    protected $fillable = [
        'class_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function hbsClass(): BelongsTo
    {
        return $this->belongsTo(HbsClass::class, 'class_id');
    }
}
