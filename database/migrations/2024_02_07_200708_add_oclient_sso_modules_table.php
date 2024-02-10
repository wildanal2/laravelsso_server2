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
        Schema::table('sso_modules', function (Blueprint $table) {
            $table->string('oclient_id')->nullable()->after('codename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sso_modules', function (Blueprint $table) {
            $table->dropColumn('oclient_id');
        });
    }
};
