<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom product_id ke tabel projects
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->constrained('products')->after('lead_id');
        });

        // Hapus tabel pivot yang tidak digunakan lagi
        Schema::dropIfExists('product_project');
    }

    public function down(): void
    {
        // Logika untuk rollback jika diperlukan
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        // Buat kembali tabel pivot jika di-rollback
        Schema::create('product_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
        });
    }
};
