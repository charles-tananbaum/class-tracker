<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HbsClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'user_id',
        'code',
        'name',
    ];

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class, 'class_id');
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

