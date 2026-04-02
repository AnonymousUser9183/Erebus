<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - adds missing columns to existing table
     * This is the CORRECT migration to use for an existing support_requests table
     */
    public function up()
    {
        // Check if table exists before modifying it
        if (Schema::hasTable('support_requests')) {
            Schema::table('support_requests', function (Blueprint $table) {
                // Add subject column if it doesn't exist
                if (!Schema::hasColumn('support_requests', 'subject')) {
                    $table->string('subject')->nullable()->after('title');
                }
                
                // Add category column if it doesn't exist
                if (!Schema::hasColumn('support_requests', 'category')) {
                    $table->enum('category', ['billing', 'technical', 'account', 'other'])->nullable()->after('subject');
                }
            });
        }
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        if (Schema::hasTable('support_requests')) {
            Schema::table('support_requests', function (Blueprint $table) {
                if (Schema::hasColumn('support_requests', 'subject')) {
                    $table->dropColumn('subject');
                }
                
                if (Schema::hasColumn('support_requests', 'category')) {
                    $table->dropColumn('category');
                }
            });
        }
    }
};
