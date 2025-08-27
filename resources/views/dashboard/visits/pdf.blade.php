<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'dejavusans', sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.4;
            font-size: 14px;
        }

        h2,
        h3 {
            color: #0d6efd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #f0f0f0;
        }

        .section-title {
            background-color: #e9ecef;
            padding: 5px 10px;
            margin-top: 15px;
            font-weight: bold;
            border-radius: 4px;
        }

        img {
            max-width: 150px;
            max-height: 150px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .report-header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
    </style>

</head>

<body>
    <div class="report-header">
        <h2>📋 تقرير زيارة المريض #{{ $visit->id }}</h2>
        <p>تاريخ الزيارة: {{ $visit->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="section-title">👨‍⚕️ معلومات المريض والطبيب</div>
    <table>
        <tr>
            <th>المريض</th>
            <td>{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</td>
        </tr>
        <tr>
            <th>الطبيب</th>
            <td>{{ $visit->appointment->doctor->first_name ?? '-' }} {{ $visit->appointment->doctor->last_name ?? '-' }}
            </td>
        </tr>
        <tr>
            <th>القسم</th>
            <td>{{ $visit->department->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>التشخيص</th>
            <td>{{ $visit->diagnosis ?? '-' }}</td>
        </tr>
        <tr>
            <th>ملاحظات</th>
            <td>{{ $visit->notes ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">💊 الوصفات الطبية</div>
    @if($visit->prescriptions->count() > 0)
        <table>
            <tr>
                <th>الدواء</th>
                <th>الجرعة</th>
                <th>المدة</th>
                <th>ملاحظات</th>
            </tr>
            @foreach($visit->prescriptions as $pres)
                <tr>
                    <td>{{ $pres->medicine_name }}</td>
                    <td>{{ $pres->dosage }}</td>
                    <td>{{ $pres->duration }}</td>
                    <td>{{ $pres->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>لا توجد وصفات طبية.</p>
    @endif

    <h2>تفاصيل الزيارة #{{ $visit->id }}</h2>
    <p><strong>المريض:</strong> {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
    <p><strong>الطبيب:</strong> {{ $visit->appointment->doctor->first_name ?? '-' }}
        {{ $visit->appointment->doctor->last_name ?? '-' }}
    </p>
    <p><strong>القسم:</strong> {{ $visit->department->name ?? '-' }}</p>
    <p><strong>التشخيص:</strong> {{ $visit->diagnosis ?? '-' }}</p>
    <p><strong>الملاحظات:</strong> {{ $visit->notes ?? '-' }}</p>

    <div class="section-title">💊 الوصفات الطبية</div>
    @if($visit->prescriptions->count() > 0)
        <table>
            <tr>
                <th>الدواء</th>
                <th>الجرعة</th>
                <th>المدة</th>
                <th>ملاحظات</th>
            </tr>
            @foreach($visit->prescriptions as $pres)
                <tr>
                    <td>{{ $pres->medicine_name }}</td>
                    <td>{{ $pres->dosage }}</td>
                    <td>{{ $pres->duration }}</td>
                    <td>{{ $pres->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>لا توجد وصفات طبية.</p>
    @endif

    <div class="section-title">🧪 التحاليل المخبرية</div>
    @if($visit->labTests->count() > 0)
        <table>
            <tr>
                <th>النتيجة</th>
                <th>تقرير فني</th>
                <th>الفني</th>
            </tr>
            @foreach($visit->labTests as $lab)
                <tr>
                    <td>{{ $lab->result ?? '-' }}</td>
                    <td>{{ $lab->technical_report ?? '-' }}</td>
                    <td>{{ $lab->technician_name ?? '-' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>لا توجد تحاليل مخبرية.</p>
    @endif

    <div class="section-title">⚕️ العمليات</div>
    @if($visit->surgeries->count() > 0)
        @foreach($visit->surgeries as $surgery)
            <p><strong>نوع العملية:</strong> {{ $surgery->surgery_type }}</p>
            <p><strong>تاريخ البداية:</strong> {{ $surgery->start_time }}</p>
            <p><strong>تاريخ النهاية:</strong> {{ $surgery->end_time }}</p>
            <p><strong>ملاحظات:</strong> {{ $surgery->notes ?? '-' }}</p>

            @if($surgery->procedures->count() > 0)
                <p><strong>تفاصيل العملية:</strong></p>
                <ul>
                    @foreach($surgery->procedures as $proc)
                        <li>{{ $proc->procedure_type }} - {{ $proc->equipment ?? '-' }}</li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    @else
        <p>لا توجد عمليات.</p>
    @endif

    <div class="section-title">🖼️ صور الأشعة</div>
    @if($visit->xRayImages->count() > 0)
        @foreach($visit->xRayImages as $xray)
            <p><strong>تقرير فني:</strong> {{ $xray->technical_report ?? '-' }}</p>
            <p><strong>الفني:</strong> {{ $xray->technician_name ?? '-' }}</p>
            @if($xray->image_path)
                <img src="{{ public_path($xray->image_path) }}" alt="Xray Image">
            @endif
        @endforeach
    @else
        <p>لا توجد صور أشعة.</p>
    @endif
</body>

</html>