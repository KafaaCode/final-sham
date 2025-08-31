<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabMessage;
use App\Models\User;
use App\Models\Visit;

class LabMessageSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء رسائل تجريبية للمخبر
        $doctors = User::whereHas('roles', function($query) {
            $query->where('name', 'الدكتور');
        })->get();

        $patients = User::whereHas('roles', function($query) {
            $query->where('name', 'المريض');
        })->get();

        $visits = Visit::take(5)->get();

        if ($doctors->count() > 0 && $patients->count() > 0 && $visits->count() > 0) {
            $testTypes = [
                'تحليل دم شامل (CBC)',
                'تحليل كيمياء الدم',
                'تحليل وظائف الكبد',
                'تحليل وظائف الكلى',
                'تحليل هرمونات الغدة الدرقية',
                'مزرعة بكتيرية',
                'تحليل بول شامل'
            ];

            $priorities = ['عادية', 'عاجلة', 'طارئة'];

            for ($i = 0; $i < 10; $i++) {
                LabMessage::create([
                    'patient_id' => $patients->random()->id,
                    'visit_id' => $visits->random()->id,
                    'doctor_id' => $doctors->random()->id,
                    'test_type' => $testTypes[array_rand($testTypes)],
                    'test_details' => 'تحليل شامل مع التركيز على القيم الطبيعية والمرضية',
                    'medical_info' => 'مريض يعاني من أعراض غير محددة، يرجى إجراء التحليل بدقة',
                    'message' => 'يرجى إجراء التحليل المطلوب وإرسال النتائج في أقرب وقت ممكن',
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => 'جديد',
                ]);
            }
        }
    }
}
