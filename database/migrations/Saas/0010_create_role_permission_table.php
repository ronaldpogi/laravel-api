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
        Schema::create('saas_role_permission', function (Blueprint $table) {
            $table->foreignUuid('role_id')->constrained('saas_roles')->cascadeOnDelete();
            $table->foreignUuid('permission_id')->constrained('saas_permissions')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_role_permission');
    }
};
