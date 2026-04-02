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
        Schema::create('private_mirror_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->index();
            $table->enum('status', ['pending', 'assigned'])->default('pending');
            $table->text('assigned_mirror')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure only one pending request per user
            $table->unique(['user_id', 'status'], 'unique_pending_request_per_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_mirror_requests');
    }
};
