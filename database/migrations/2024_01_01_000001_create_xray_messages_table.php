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
        Schema::create('xray_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('examination_type');
            $table->text('examination_details')->nullable();
            $table->text('medical_info')->nullable();
            $table->text('message')->nullable();
            $table->enum('priority', ['عادية', 'عاجلة', 'طارئة'])->default('عادية');
            $table->enum('status', ['جديد', 'قيد التنفيذ', 'مكتمل'])->default('جديد');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xray_messages');
    }
};
