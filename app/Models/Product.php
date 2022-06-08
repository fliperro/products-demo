<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function lowestPrice(): HasOne
    {
        return $this->hasOne(Price::class)->ofMany('value', 'min');
    }

    public function highestPrice(): HasOne
    {
        return $this->hasOne(Price::class)->ofMany('value', 'max');
    }
}
