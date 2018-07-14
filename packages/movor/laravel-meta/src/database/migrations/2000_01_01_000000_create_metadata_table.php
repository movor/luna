<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-meta.table_name'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->unsignedInteger('metable_id')->default(0);
            $table->string('metable_type')->default('');
            $table->text('data');
            $table->timestamps();

            $table->unique(['type', 'metable_id', 'metable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-meta.table_name'));
    }
}