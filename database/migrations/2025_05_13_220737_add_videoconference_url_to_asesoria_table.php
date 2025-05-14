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
        Schema::table('asesoria', function (Blueprint $table) {
            $table->string('videoconference_url')->nullable()->after('estado');
            $table->string('meet_code')->nullable()->after('videoconference_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesoria', function (Blueprint $table) {
            $table->dropColumn('videoconference_url');
            $table->dropColumn('meet_code');
        });
    }
};
