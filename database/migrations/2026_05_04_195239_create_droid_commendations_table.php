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
        Schema::create('droid_commendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('droid_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('visitor_id')->nullable()->index();
            $table->timestamps();

            // Prevent duplicate commendations from the same person for the same droid
            $table->unique(['droid_id', 'user_id'], 'unique_user_commendation');
            $table->unique(['droid_id', 'visitor_id'], 'unique_visitor_commendation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('droid_commendations');
    }
};
