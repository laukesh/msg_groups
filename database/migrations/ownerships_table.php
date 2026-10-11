<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ownerships', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 50)->nullable()->unique();
            $table->text('description')->nullable();

            $table->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            $table->unsignedInteger('sort_order')->default(0);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'sort_order']);
        });

        if (
            Schema::hasTable('assets') &&
            !Schema::hasColumn('assets', 'ownership_id')
        ) {
            Schema::table('assets', function (Blueprint $table) {
                $table->foreignId('ownership_id')
                    ->nullable()
                    ->constrained('ownerships')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('assets') &&
            Schema::hasColumn('assets', 'ownership_id')
        ) {
            Schema::table('assets', function (Blueprint $table) {
                $table->dropForeign(['ownership_id']);
                $table->dropColumn('ownership_id');
            });
        }

        Schema::dropIfExists('ownerships');
    }
};