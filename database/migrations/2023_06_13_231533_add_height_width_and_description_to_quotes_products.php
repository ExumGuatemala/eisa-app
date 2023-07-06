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
        Schema::table('quotes_products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('price');
            $table->decimal('height', 8, 2)->nullable()->after('description');
            $table->decimal('width', 8, 2)->nullable()->after('height');
            $table->decimal('totalm', 8, 2)->nullable()->after('width');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('quotes_products', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('totalm');
        });
    }
};
