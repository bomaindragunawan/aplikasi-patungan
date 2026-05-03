<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'group_id',
        'name',
        'email',
        'phone',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function expenseSplits(): HasMany
    {
        return $this->hasMany(ExpenseSplit::class);
    }

    public function settlementsFrom(): HasMany
    {
        return $this->hasMany(Settlement::class, 'from_member_id');
    }

    public function settlementsTo(): HasMany
    {
        return $this->hasMany(Settlement::class, 'to_member_id');
    }
}
