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
        Schema::table('quotes', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->default(1)->change();
            $table->unsignedBigInteger('pricetype_id')->nullable()->after('client_id');
            $table->foreign('pricetype_id')->references('id')->on('price_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['pricetype_id']);
            $table->dropColumn('pricetype_id');
        });
    }
};
