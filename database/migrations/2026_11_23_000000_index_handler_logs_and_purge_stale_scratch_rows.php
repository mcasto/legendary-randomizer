<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Setups are soft-deleted, so the cascade on candidates never fired and
        // handler_logs has no FK at all. These rows are build-time scratch data
        // and are useless once their setup is trashed.
        DB::table('candidates')
            ->whereIn('setup_id', DB::table('setups')->whereNotNull('deleted_at')->select('id'))
            ->delete();

        DB::table('handler_logs')
            ->whereNotIn('setup_id', DB::table('setups')->whereNull('deleted_at')->select('id'))
            ->delete();

        Schema::table('handler_logs', function (Blueprint $table) {
            $table->index(['setup_id', 'entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::table('handler_logs', function (Blueprint $table) {
            $table->dropIndex(['setup_id', 'entity_type', 'entity_id']);
        });
    }
};
