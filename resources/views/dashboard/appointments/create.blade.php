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

            {{-- القسم أولاً --}}
            <div class="mb-1">
                <label>القسم</label>
                <select id="departmentSelect" name="department_id" class="form-control" required>
                    <option value="">اختر القسم</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- الدكتور مرتبط بالقسم --}}
            <div class="mb-1">
                <label>الدكتور</label>
                <select id="doctorSelect" name="doctor_id" class="form-control" required disabled>
                    <option value="">اختر القسم أولاً</option>
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

            
            const departmentSelect = document.getElementById("departmentSelect");
            const doctorSelect     = document.getElementById("doctorSelect");

            
            const doctorsByDept = @json(
                $departments->mapWithKeys(fn($d) => [$d->id => $d->doctors->map(fn($doc) => [
                    'id' => $doc->id,
                    'name' => $doc->first_name . ' ' . $doc->last_name
                ])])
            );

            departmentSelect.addEventListener("change", function() {
                const deptId = this.value;
                doctorSelect.innerHTML = '<option value="">اختر الدكتور</option>';

                if (deptId && doctorsByDept[deptId]) {
                    doctorsByDept[deptId].forEach(doc => {
                        const opt = document.createElement("option");
                        opt.value = doc.id;
                        opt.textContent = doc.name;
                        doctorSelect.appendChild(opt);
                    });
                    doctorSelect.disabled = false;
                } else {
                    doctorSelect.innerHTML = '<option value="">اختر القسم أولاً</option>';
                    doctorSelect.disabled = true;
                }
            });

            // الدوال الخاصة بالوقت
            const buildDT = (d, t) => `${d} ${t}:00`;

            function syncHidden() {
                const d = dateInput.value;
                const s = startInput.value;
                const e = endInput.value;
                startHidden.value = (d && s) ? buildDT(d, s) : "";
                endHidden.value   = (d && e) ? buildDT(d, e) : "";
            }

            startInput.addEventListener("change", function() {
                endInput.min = startInput.value || "";
                if (endInput.value && startInput.value && endInput.value < startInput.value) {
                    endInput.value = "";
                }
                syncHidden();
            });

            endInput.addEventListener("change", syncHidden);
            dateInput.addEventListener("change", syncHidden);

            form.addEventListener("submit", function(e) {
                if (!dateInput.value || !startInput.value || !endInput.value) {
                    e.preventDefault();
                    alert("الرجاء إدخال التاريخ ووقت البداية والنهاية");
                    return;
                }
                syncHidden();
            });
        });
    </script>
@endsection
