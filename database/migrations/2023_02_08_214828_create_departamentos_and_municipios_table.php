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
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departamento_id');
            $table->string('name');

            $table->foreign('departamento_id')->references('id')->on('departamentos');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('municipio_id')->nullable()->after('address');

            $table->foreign('municipio_id')->references('id')->on('municipios');
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
            $table->dropForeign(['municipio_id']);
            $table->dropColumn('municipio_id');
        });
        Schema::dropIfExists('municipios');
        Schema::dropIfExists('departamentos');
    }
};
