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
        Schema::create('lab_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('test_type')->nullable(); // نوع التحليل
            $table->text('test_details')->nullable(); // تفاصيل التحليل
            $table->text('medical_info')->nullable(); // معلومات طبية
            $table->text('message')->nullable(); // رسالة الطبيب
            $table->enum('priority', ['عادية', 'عاجلة', 'طارئة'])->default('عادية'); // أولوية التحليل
            $table->enum('status', ['جديد', 'قيد التنفيذ', 'مكتمل', 'ملغي'])->default('جديد');
            $table->foreignId('lab_technician_id')->nullable()->constrained('users')->onDelete('set null'); // فني المخبر
            $table->text('technician_notes')->nullable(); // ملاحظات الفني
            $table->timestamp('completed_at')->nullable(); // تاريخ الإنجاز
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_messages');
    }
};
