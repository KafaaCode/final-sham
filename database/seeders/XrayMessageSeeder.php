<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\XrayMessage;
use App\Models\User;
use App\Models\Visit;

class XrayMessageSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء رسائل تجريبية للأشعة
        $doctors = User::whereHas('roles', function($query) {
            $query->where('name', 'الدكتور');
        })->get();

        $patients = User::whereHas('roles', function($query) {
            $query->where('name', 'المريض');
        })->get();

        $visits = Visit::take(5)->get();

        if ($doctors->count() > 0 && $patients->count() > 0 && $visits->count() > 0) {
            $examinationTypes = [
                'أشعة سينية للصدر',
                'أشعة مقطعية للدماغ',
                'رنين مغناطيسي للعمود الفقري',
                'أشعة بالموجات فوق الصوتية للبطن',
                'أشعة سينية للأطراف العلوية'
            ];

            $priorities = ['عادية', 'عاجلة', 'طارئة'];

            for ($i = 0; $i < 10; $i++) {
                XrayMessage::create([
                    'patient_id' => $patients->random()->id,
                    'visit_id' => $visits->random()->id,
                    'doctor_id' => $doctors->random()->id,
                    'examination_type' => $examinationTypes[array_rand($examinationTypes)],
                    'examination_details' => 'فحص شامل للمنطقة المحددة مع التركيز على النقاط المشكوك فيها',
                    'medical_info' => 'مريض يعاني من ألم في المنطقة المحددة، لا توجد حساسية معروفة',
                    'message' => 'يرجى إجراء الفحص بدقة وإرسال التقرير في أقرب وقت ممكن',
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => 'جديد',
                ]);
            }
        }
    }
}
