<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Definisikan status menggunakan Enum untuk kejelasan
    protected $casts = [
        'status' => \App\Enums\ProjectStatus::class,
        'approved_at' => 'datetime',
    ];

    // Project ini milik siapa?
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    // Project ini untuk layanan/produk apa saja? (Many-to-Many)
    public function product(): BelongsTo
    {
        // Pivot table 'project_product' akan dibuat
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Siapa manager yang melakukan approval?
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
