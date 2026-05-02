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
        Schema::table('droid_scans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('visitor_id')->nullable()->after('user_id')->index();
            $table->dropUnique(['user_id', 'droid_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('droid_scans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn('visitor_id');
            $table->unique(['user_id', 'droid_id']);
        });
    }
};
