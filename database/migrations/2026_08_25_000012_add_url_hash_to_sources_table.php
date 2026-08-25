<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->char('url_hash', 64)->nullable()->after('url');
        });

        DB::table('sources')
            ->select(['id', 'url'])
            ->orderBy('id')
            ->chunkById(100, function ($sources): void {
                foreach ($sources as $source) {
                    DB::table('sources')
                        ->where('id', $source->id)
                        ->update(['url_hash' => hash('sha256', $source->url)]);
                }
            });

        Schema::table('sources', function (Blueprint $table) {
            $table->char('url_hash', 64)->nullable(false)->change();
            $table->unique('url_hash');
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropUnique(['url_hash']);
            $table->dropColumn('url_hash');
        });
    }
};
