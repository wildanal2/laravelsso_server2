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
        Schema::create('sso_module_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->string('name');
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('sso_modules')->onDelete('cascade'); 
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sso_module_features');
    }
};
