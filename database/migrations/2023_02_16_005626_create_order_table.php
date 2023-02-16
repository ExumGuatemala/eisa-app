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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->timestamp('pre_delivery');
            $table->timestamp('delivery');
            $table->unsignedBigInteger('quote_id');
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes');
        });

        Schema::rename('quotes_products', 'quotes_orders_products');

        Schema::table('quotes_orders_products', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('quote_id');

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes_orders_products', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
        Schema::rename('quotes_orders_products', 'quotes_products');
        Schema::dropIfExists('orders');
    }
};
