<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('last_digits', 4);

            $table->decimal('credit_limit', 15, 2)
                ->default(0);

            $table->decimal('used_limit', 15, 2)
                ->default(0);

            $table->unsignedTinyInteger('closing_day');

            $table->unsignedTinyInteger('due_day');

            $table->boolean('is_active')
                ->default(true);

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
