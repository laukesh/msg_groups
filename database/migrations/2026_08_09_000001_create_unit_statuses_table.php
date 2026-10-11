<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('unit_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name');
            $table->text('description')->nullable();
            $table->string('color_code', 20)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_statuses');
    }
}
