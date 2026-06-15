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
        Schema::table('events', function (Blueprint $table) {
            $table->integer('capacity')->default(500)->after('location');
            $table->boolean('is_qr_active')->default(true)->after('status');
            $table->boolean('certificate_automation_active')->default(false)->after('is_qr_active');
            $table->string('certificate_template')->nullable()->after('certificate_automation_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'is_qr_active', 'certificate_automation_active', 'certificate_template']);
        });
    }
};
