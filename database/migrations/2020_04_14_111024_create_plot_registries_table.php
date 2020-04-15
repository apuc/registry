<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlotRegistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plot_registries', function (Blueprint $table) {
            $table->id();
            $table->string('cadastral_number');
            $table->string('address');
            $table->decimal('price', 11, 4);
            $table->decimal('area', 11, 4);
            $table->json('_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plot_registries');
    }
}
