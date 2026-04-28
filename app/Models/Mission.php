<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mission extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = ['name'];

    /**
     * @return HasMany<Satellite, $this>
     */
    public function satellites(): HasMany
    {
        return $this->hasMany(Satellite::class);
    }
}
