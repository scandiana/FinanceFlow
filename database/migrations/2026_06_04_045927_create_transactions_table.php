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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();

        $table->enum('type', [
            'income',
            'expense',
            'transfer'
        ]);

        $table->string('description');

        $table->decimal('amount', 15, 2);

        $table->date('transaction_date');

        $table->date('due_date')->nullable();

        $table->enum('status', [
            'pending',
            'scheduled',
            'completed',
            'cancelled'
        ])->default('pending');

        $table->foreignId('bank_account_id')
              ->constrained()
              ->cascadeOnUpdate();

        $table->foreignId('category_id')
              ->constrained()
              ->cascadeOnUpdate();

        $table->foreignId('client_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->foreignId('created_by')
              ->constrained('users')
              ->cascadeOnUpdate();

        $table->text('notes')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
