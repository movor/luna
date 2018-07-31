<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('from', 256);
            $table->string('to', 256);
            $table->unsignedSmallInteger('status');
            $table->unsignedInteger('hits')->default(0);
            $table->text('data');

            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();

            $table->unique(['from', 'to']);

            // TODO -  Indices
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirects');
    }
}