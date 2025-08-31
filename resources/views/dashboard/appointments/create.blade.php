@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة موعد جديد</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label>الدكتور</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">اختر الدكتور</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-1">
                <label>القسم</label>
                <select name="department_id" class="form-control" required>
                    <option value="">اختر القسم</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-1">
                <label>📅 التاريخ</label>
                <input type="date" id="appointment_date" class="form-control" required>
            </div>

            <div class="mb-1">
                <label>⏰ وقت البداية</label>
                <input type="time" id="start_time" class="form-control" required>
            </div>

            <div class="mb-1">
                <label>⏰ وقت النهاية</label>
                <input type="time" id="end_time" class="form-control" required>
            </div>

            {{-- الحقول المخفية المُرسلة للسيرفر --}}
            <input type="hidden" name="appointment_start_time" id="appointment_start_time">
            <input type="hidden" name="appointment_end_time" id="appointment_end_time">

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("appointmentForm");

            const dateInput   = document.getElementById("appointment_date");
            const startInput  = document.getElementById("start_time");
            const endInput    = document.getElementById("end_time");

            const startHidden = document.getElementById("appointment_start_time");
            const endHidden   = document.getElementById("appointment_end_time");

            const buildDT = (d, t) => `${d} ${t}:00`;

            function syncHidden() {
                const d = dateInput.value;
                const s = startInput.value;
                const e = endInput.value;
                startHidden.value = (d && s) ? buildDT(d, s) : "";
                endHidden.value   = (d && e) ? buildDT(d, e) : "";
            }

            // منع نهاية أقل من البداية + مزامنة فورية
            startInput.addEventListener("change", function() {
                endInput.min = startInput.value || "";
                if (endInput.value && startInput.value && endInput.value < startInput.value) {
                    endInput.value = "";
                }
                syncHidden();
            });

            endInput.addEventListener("change", syncHidden);
            dateInput.addEventListener("change", syncHidden);

            // قبل الإرسال تأكيد الامتلاء والمزامنة
            form.addEventListener("submit", function(e) {
                if (!dateInput.value || !startInput.value || !endInput.value) {
                    e.preventDefault();
                    alert("الرجاء إدخال التاريخ ووقت البداية والنهاية");
                    return;
                }
                syncHidden(); // تأكيد تعبئة الحقول المخفية
            });
        });
    </script>
@endsection
