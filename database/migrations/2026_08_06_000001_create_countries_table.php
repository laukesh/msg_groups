<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso2', 2)->nullable()->index();
            $table->string('iso3', 3)->nullable()->index();
            $table->string('numeric_code', 10)->nullable();
            $table->string('phone_code', 30)->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
