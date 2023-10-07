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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('client_name');
            $table->dropColumn('order_key');
            $table->unsignedBigInteger('quote_id')->nullable()->after('description');
            $table->foreign('quote_id')->references('id')->on('quotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('client_name');
            $table->string('order_key');
            $table->dropForeign(['quote_id']);
            $table->dropColumn('quote_id');
        });
    }
};
