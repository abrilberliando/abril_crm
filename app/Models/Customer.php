<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Customer bisa memiliki banyak project yang sudah di-approve
    public function projects(): HasMany
    {
        // Hanya tampilkan project yang sudah approved
        return $this->hasMany(Project::class)->where('status', \App\Enums\ProjectStatus::Approved);
    }

    // Relasi 'hasManyThrough' untuk mendapatkan semua layanan
    // Seorang Customer memiliki banyak Product melalui Project
    public function services()
    {
        return $this->hasManyThrough(
            Product::class,
            Project::class,
            'customer_id', // Foreign key on projects table...
            'id', // Foreign key on products table...
            'id', // Local key on customers table...
            'id' // Local key on projects table...
        )->join('project_product', 'products.id', '=', 'project_product.product_id')
         ->where('projects.id', '=', 'project_product.project_id');
    }
}
