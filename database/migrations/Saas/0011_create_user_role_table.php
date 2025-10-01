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
        Schema::create('saas_role_user', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('saas_users')->cascadeOnDelete();
            $table->foreignUuid('role_id')->constrained('saas_roles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_role_user');
    }
};
