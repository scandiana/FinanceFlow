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
        Schema::table('transactions', function (Blueprint $table) {
    
            $table->foreignId('card_id')
                  ->nullable()
                  ->after('client_id')
                  ->constrained()
                  ->nullOnDelete();
    
        });
    }
    
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
    
            $table->dropConstrainedForeignId('card_id');
    
        });
    }
};
