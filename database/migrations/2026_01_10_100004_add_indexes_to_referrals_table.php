<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Индексы для запросов к referrals.
 *
 * На MySQL (InnoDB) и PostgreSQL внешние ключи из create_referrals_table уже создают
 * неявные одноколоночные индексы на referrer_master_id / referred_master_id, поэтому
 * на этих СУБД два явных индекса ниже избыточны — миграцию можно не применять.
 *
 * Фактический рантайм проекта (см. .env и README) — SQLite, который неявные индексы
 * на FK НЕ создаёт: без этих двух индексов выборка по referrer_master_id
 * (GET /api/referrals/my) и по referred_master_id (attach, pipeline вознаграждений)
 * деградирует до полного сканирования таблицы.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            // GET /api/referrals/my: WHERE referrer_master_id = ? ORDER BY id DESC.
            // Одноколоночный индекс даёт прямой обход по rowid в обратном порядке,
            // без дополнительной сортировки страницы.
            $table->index('referrer_master_id');

            // POST /api/referrals/attach (firstOrCreate) и поиск Pending-реферала
            // в ReferralRewardPipeline: WHERE referred_master_id = ? [AND status = ?].
            // По одному приведённому мастеру строка одна (firstOrCreate),
            // поэтому фильтрация по статусу после точного поиска ничего не стоит.
            $table->index('referred_master_id');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropIndex(['referrer_master_id']);
            $table->dropIndex(['referred_master_id']);
        });
    }
};
