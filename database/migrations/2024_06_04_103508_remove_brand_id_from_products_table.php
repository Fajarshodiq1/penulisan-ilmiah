<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBrandIdFromProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus constraint kunci asing terlebih dahulu
            $table->dropForeign(['brand_id']);
            // Hapus kolom brand_id
            $table->dropColumn('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kembali kolom brand_id
            $table->unsignedBigInteger('brand_id')->nullable();
            // Tambahkan kembali constraint kunci asing
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }
}