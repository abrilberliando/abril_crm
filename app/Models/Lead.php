<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Sebuah lead bisa memiliki banyak project yang diajukan
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
