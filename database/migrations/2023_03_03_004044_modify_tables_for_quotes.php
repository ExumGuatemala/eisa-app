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
        Schema::dropIfExists('quotes_products_pricetypes');

        Schema::create('quotes_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 9, 2)->default(0);
            $table->timestamps();

            $table->foreign('quote_id')
                ->references('id')
                ->on('quotes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('total', 9, 2)->default(0)->change();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('phone2')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('quotes_products', 'quotes_products_pricetypes');

        Schema::table('quotes_products_pricetypes', function (Blueprint $table) {
            $table->unsignedBigInteger('pricetype_id')->nullable()->after('product_id');

            $table->foreign('pricetype_id')->references('id')->on('products_pricetypes');
        });
    }
};
