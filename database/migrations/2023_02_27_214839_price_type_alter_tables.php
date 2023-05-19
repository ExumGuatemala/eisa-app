<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_pricetypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('pricetype_id')->nullable();
            $table->decimal('price', 9, 2)->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('pricetype_id')->references('id')->on('price_types')->onDelete('cascade');

        });

        Schema::rename('quotes_products', 'quotes_products_pricetypes');

        Schema::table('quotes_products_pricetypes', function (Blueprint $table) {
            $table->unsignedBigInteger('pricetype_id')->nullable()->after('product_id');
            $table->foreign('pricetype_id')->references('id')->on('products_pricetypes');

            // $table->foreign('pricetype_id')->references('id')->on('products_pricetypes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes_products_pricetypes', function (Blueprint $table) {
            $table->dropForeign(['pricetype_id']);
            $table->dropColumn('pricetype_id');
        });

        Schema::rename('quotes_products_pricetypes', 'quotes_products');
        Schema::dropIfExists('products_pricetypes');
    }
};
