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
        Schema::create('price_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('pricetype_id')->nullable()->after('phone2');

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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['pricetype_id']);
            $table->dropColumn('pricetype_id');
        });
        Schema::dropIfExists('price_types');
    }
};
