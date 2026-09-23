<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_earnings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('referrer_master_id')->constrained('masters')->cascadeOnDelete();
            $table->foreignId('referred_master_id')->constrained('masters')->cascadeOnDelete();
            $table->foreignId('referral_id')->constrained('referrals')->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();

            $table->unsignedInteger('payment_amount');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('percent');
            $table->string('status')->default('pending');

            // Один составной индекс закрывает все чтения таблицы:
            // фильтры WHERE referrer_master_id = ? (список /my, сводка),
            // суммы по статусу (pending/paid) и группировки по referred_master_id.
            // По одному рефереру строк немного (не больше числа его рефералов),
            // поэтому отдельных индексов на остальные колонки не нужно.
            $table->index(['referrer_master_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_earnings');
    }
};
