@extends('layouts.app')

@section('content')
    @if (auth()->user()->hasRole('موظف الاستقبال'))
        <div class="container">
            <h2 class="mb-1">لوحة تحكم المرضى</h2>
            <div class="row mb-1">
                <div class="col-md-6">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <h5 class=" text-white">إجمالي المرضى</h5>
                            <h3 class=" text-white">{{ $totalPatients }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success">
                        <div class="card-body">
                            <h5 class=" text-white">مرضى اليوم</h5>
                            <h3 class=" text-white">{{ $todayPatients }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <form method="GET" class="mb-1">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن مريض بالرقم الوطني"
                        value="{{ $search }}">
                    <button type="submit" class="btn btn-primary">بحث</button>
                    <a href="/dashboard" class="btn btn-danger">الغاء</a>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الاسم الاول</th>
                        <th>الاسم الثاني</th>
                        <th>اسم الاب</th>
                        <th>اسم الام</th>
                        <th>الرقم الوطني</th>
                        <th>تاريخ الدخول</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->first_name }}</td>
                            <td>{{ $patient->last_name }}</td>
                            <td>{{ $patient->father_name }}</td>
                            <td>{{ $patient->mother_name }}</td>
                            <td>{{ $patient->national_id }}</td>
                            <td>{{ $patient->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">لا يوجد مرضى</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $patients->links() }}
        </div>
    @elseif(auth()->user()->hasRole('المريض'))
        <div class="container">
            <h2 class="mb-1 text-center">المواعيد المتاحة</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- فلترة -->
            <form method="GET" class="mb-1">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <select name="department_id" class="form-select">
                            <option value="">جميع الأقسام</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" @if(request('department_id') == $dept->id) selected @endif>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">فلترة</button>
                    </div>
                </div>
            </form>

            <div class="row">
                @foreach($appointments as $appointment)
                    <div class="col-md-4 mb-3">
                        <div
                            class="card shadow-sm h-100 
                                                                                                                                                                                                                                                                                                        @if($appointment->status == 'متوفر') border-success @else border-danger @endif">
                            <div
                                class="card-header 
                                                                                                                                                                                                                                                                                                        @if($appointment->status == 'متوفر') bg-success text-white @else bg-danger text-white @endif">
                                {{ $appointment->status }}
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $appointment->doctor->name }}</h5>
                                <p class="card-text">
                                    <strong>الطبيب:</strong> {{ $appointment->doctor->first_name }}
                                    {{ $appointment->doctor->last_name }}<br>
                                    <strong>القسم:</strong> {{ $appointment->department->name }} <br>
                                    <strong>التاريخ:</strong> {{ date('Y-m-d', strtotime($appointment->appointment_start_time)) }}
                                    <br>
                                    <strong>وقت البداية:</strong> {{ date('H:i', strtotime($appointment->appointment_start_time)) }}
                                    <br>
                                    <strong>وقت النهاية:</strong> {{ date('H:i', strtotime($appointment->appointment_end_time)) }}
                                </p>

                                @if($appointment->status == 'متوفر')
                                    <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#bookModal"
                                        data-id="{{ $appointment->id }}"
                                        data-doctor="{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}"
                                        data-department="{{ $appointment->department->name }}"
                                        data-date="{{ date('Y-m-d', strtotime($appointment->appointment_start_time)) }}"
                                        data-start="{{ date('H:i', strtotime($appointment->appointment_start_time)) }}"
                                        data-end="{{ date('H:i', strtotime($appointment->appointment_end_time)) }}">
                                        حجز الآن
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        غير متاح
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- نافذة منبثقة لإنشاء زيارة -->
        <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="bookForm" method="POST" action="{{ route('visits.storeForUser') }}">
                    @csrf
                    <input type="hidden" name="appointment_id" id="modalAppointmentId">

                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="bookModalLabel">حجز موعد جديد</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p>سيتم حجز موعد لك مع:</p>
                            <p><strong>👨‍⚕️ الطبيب:</strong> <span id="modalDoctor"></span></p>
                            <p><strong>🏥 القسم:</strong> <span id="modalDepartment"></span></p>
                            <p><strong>📅 التاريخ:</strong> <span id="modalDate"></span></p>
                            <p><strong>🕒 وقت البداية:</strong> <span id="modalStartTime"></span></p>
                            <p><strong>🕒 وقت النهاية:</strong> <span id="modalEndTime"></span></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">تأكيد وإنشاء الزيارة ✅</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var bookModal = document.getElementById('bookModal');
                bookModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var appointmentId = button.getAttribute('data-id');
                    var doctorName = button.getAttribute('data-doctor');
                    var departmentName = button.getAttribute('data-department');
                    var date = button.getAttribute('data-date');
                    var startTime = button.getAttribute('data-start');
                    var endTime = button.getAttribute('data-end');

                    document.getElementById('modalAppointmentId').value = appointmentId;
                    document.getElementById('modalDoctor').textContent = doctorName;
                    document.getElementById('modalDepartment').textContent = departmentName;
                    document.getElementById('modalDate').textContent = date;
                    document.getElementById('modalStartTime').textContent = startTime;
                    document.getElementById('modalEndTime').textContent = endTime;
                });
            });

        </script>
    @elseif(auth()->user()->hasRole('Super Admin'))
        <div class="container">

            <h2 class="mb-1 text-center text-primary fw-bold">📊 إحصائيات الزيارات</h2>

            {{-- فلترة حسب التاريخ --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="{{ route('dashboard') }}" method="GET" class="row g-2">
                        <div class="col">
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col">
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">تصفية</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">إعادة ضبط</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- إحصائيات عامة --}}
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-4 border-primary rounded-4 p-3">
                        <h5 class="fw-bold">عدد الزيارات</h5>
                        <p class="display-6 fw-bold text-primary">{{ $totalVisits }}</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-4 border-success rounded-4 p-3">
                        <h5 class="fw-bold">عدد المرضى</h5>
                        <p class="display-6 fw-bold text-success">{{ $totalPatients }}</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-4 border-warning rounded-4 p-3">
                        <h5 class="fw-bold">عدد التحاليل</h5>
                        <p class="display-6 fw-bold text-warning">{{ $totalLabTests }}</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-4 border-info rounded-4 p-3">
                        <h5 class="fw-bold">عدد صور الأشعة</h5>
                        <p class="display-6 fw-bold text-info">{{ $totalXrays ?? 0 }}</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-4 border-danger rounded-4 p-3">
                        <h5 class="fw-bold">عدد العمليات</h5>
                        <p class="display-6 fw-bold text-danger">{{ $totalSurgeries }}</p>
                    </div>
                </div>

            </div>

            {{-- جدول الزيارات --}}
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    تفاصيل الزيارات
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>المريض</th>
                                <th>القسم</th>
                                <th>الطبيب</th>
                                <th>تاريخ الزيارة</th>
                                <th>عدد التحاليل</th>
                                <th>عدد العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adminVisits as $visit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $visit->patient->first_name ?? '-' }} {{ $visit->patient->last_name ?? '' }}</td>
                                    <td>{{ $visit->department->name ?? '-' }}</td>
                                    <td>
                                        {{ optional(optional($visit->appointment)->doctor)->first_name ?? '-' }}
                                    </td>

                                    <td>{{ $visit->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $visit->labTests->count() }}</td>
                                    <td>{{ $visit->surgeries->count() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">لا توجد زيارات في هذا النطاق</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <style>
            .card-header h5 {
                font-size: 1.1rem;
            }

            .table th,
            .table td {
                font-size: 0.95rem;
            }

            .display-6 {
                font-size: 2.2rem;
            }
        </style>
    @elseif(auth()->user()->hasRole('الدكتور'))
        <div class="container">
            <h2 class="fw-bold text-center mb-1">📋 زيارات المرضى الخاصة بي</h2>
            @if($visits->count() > 0)
                <div class="row g-4">
                    @foreach($visits as $visit)
                        <div class="col-md-4">
                            <div
                                class="card shadow-sm rounded-4 h-80 visit-card
                                    @if($visit->appointment->appointment_start_time < now()) border-secondary
                                    @else border-primary 
                                    @endif">

                                <div
                                    class="card-header text-white
                                        @if($visit->appointment->appointment_start_time < now()) bg-secondary
                                        @else bg-primary @endif
                                            rounded-top-4 text-center">
                                    {{ $visit->appointment->appointment_start_time < now() ? 'منتهي' : 'نشط' }}
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title mt-1 fw-bold">اسم المريض: {{ $visit->patient->first_name }}
                                        {{ $visit->patient->last_name }}
                                    </h5>
                                    <p><strong>🏥 القسم:</strong> {{ $visit->department->name }}</p>
                                    <p><strong>📅 التاريخ:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت البداية:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت النهاية:</strong> {{ $visit->appointment->appointment_end_time }}</p>
                                    <p><strong>📝 التشخيص:</strong> {{ $visit->diagnosis ?? '-' }}</p>
                                    <p><strong>📌 الملاحظات:</strong> {{ $visit->notes ?? '-' }}</p>
                                    <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-primary">
                                        عرض التفاصيل
                                    </a>
                                </div>
                                @if($visit->status == 5)
                                        <div class="card-footer text-muted bg-danger">
                                            <small>تم انهاء الزيارة</small>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $visits->links() }}
                </div>
            @else
                <div class="alert alert-info text-center fs-5 py-3">
                    لا توجد زيارات حالياً.
                </div>
            @endif
        </div>

        <style>
            .visit-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .visit-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            .card-body p {
                font-size: 0.95rem;
            }
        </style>
    @elseif(auth()->user()->hasRole('فني الأشعة'))
        <div class="container">
            <h2 class="fw-bold text-center mb-1">📋 طلبات صور الأشعة الحالية</h2>
            @if($xray_requests->count() > 0)
                <div class="row g-4">
                    @foreach($xray_requests as $request)
                        <div class="col-md-4">
                            <div class="card shadow-sm rounded-4 h-80 visit-card
                                @if($request->created_at < now()) border-secondary
                                @else border-primary @endif">

                                <div class="card-header text-white
                                    @if($request->created_at < now()) bg-secondary
                                    @else bg-primary @endif
                                    rounded-top-4 text-center">
                                    {{ $request->status == 'done' ? 'منتهي' : 'نشط' }}
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title mt-1 fw-bold">
                                        اسم المريض: {{ $request->patient->first_name }} {{ $request->patient->last_name }}
                                    </h5>
                                    <p><strong>📅 التاريخ:</strong> {{ $request->created_at->format('Y-m-d H:i') }}</p>
                                    <p><strong>📌 الملاحظات:</strong> {{ $request->message ?? '-' }}</p>

                                    <a href="{{ route('visits.show', $request->visit_id) }}" class="btn btn-sm btn-primary">
                                        عرض التفاصيل
                                    </a>

                                    @if($request->status == 'done')
                                        <div class="card-footer text-muted">
                                            <small>تم إنهاء الطلب</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $xray_requests->links() }}
                </div>
            @else
                <div class="alert alert-info text-center fs-5 py-3">
                    لا توجد طلبات أشعة حالية.
                </div>
            @endif
        </div>


        <style>
            .visit-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .visit-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            .card-body p {
                font-size: 0.95rem;
            }
        </style>
    @elseif(auth()->user()->hasRole('فني المخبر'))
        <div class="container">
            <h2 class="fw-bold text-center mb-1">📋 طلبات التحاليل الحالية</h2>
            @if($labTests_visits->count() > 0)
                <div class="row g-4">
                    @foreach($labTests_visits as $visit)
                        <div class="col-md-4">
                            <div
                                class="card shadow-sm rounded-4 h-80 visit-card
                                                                                                                                                                                                                                                                                        @if($visit->appointment->appointment_start_time < now()) border-secondary
                                                                                                                                                                                                                                                                                        @else border-primary @endif">
                                <div
                                    class="card-header text-white
                                                                                                                                                                                                                                                                                            @if($visit->appointment->appointment_start_time < now()) bg-secondary
                                                                                                                                                                                                                                                                                            @else bg-primary @endif
                                                                                                                                                                                                                                                                                            rounded-top-4 text-center">
                                    {{ $visit->appointment->appointment_start_time < now() ? 'منتهي' : 'نشط' }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mt-1 fw-bold">اسم المريض: {{ $visit->patient->first_name }}
                                        {{ $visit->patient->last_name }}
                                    </h5>
                                    <p><strong>🏥 القسم:</strong> {{ $visit->department->name }}</p>
                                    <p><strong>📅 التاريخ:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت البداية:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت النهاية:</strong> {{ $visit->appointment->appointment_end_time }}</p>
                                    <p><strong>📝 التشخيص:</strong> {{ $visit->diagnosis ?? '-' }}</p>
                                    <p><strong>📌 الملاحظات:</strong> {{ $visit->notes ?? '-' }}</p>
                                    <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-primary">
                                        عرض التفاصيل
                                    </a>
                                </div>
                                @if($visit->status == 5)
                                        <div class="card-footer text-muted bg-danger">
                                            <small>تم انهاء الزيارة</small>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $visits->links() }}
                </div>
            @else
                <div class="alert alert-info text-center fs-5 py-3">
                    لا توجد تحاليل حالية.
                </div>
            @endif
        </div>

        <style>
            .visit-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .visit-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            .card-body p {
                font-size: 0.95rem;
            }
        </style>
    @elseif(auth()->user()->hasRole('فني العمليات'))
        <div class="container">
            <h2 class="fw-bold text-center mb-1">📋 طلبات العلميات</h2>
            @if($surgeries_visits->count() > 0)
                <div class="row g-4">
                    @foreach($surgeries_visits as $visit)
                        <div class="col-md-4">
                            <div
                                class="card shadow-sm rounded-4 h-80 visit-card
                                                                                                                                                                                                                                                                                        @if($visit->appointment->appointment_start_time < now()) border-secondary
                                                                                                                                                                                                                                                                                        @else border-primary @endif">
                                <div
                                    class="card-header text-white
                                                                                                                                                                                                                                                                                            @if($visit->appointment->appointment_start_time < now()) bg-secondary
                                                                                                                                                                                                                                                                                            @else bg-primary @endif
                                                                                                                                                                                                                                                                                            rounded-top-4 text-center">
                                    {{ $visit->appointment->appointment_start_time < now() ? 'منتهي' : 'نشط' }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mt-1 fw-bold">اسم المريض: {{ $visit->patient->first_name }}
                                        {{ $visit->patient->last_name }}
                                    </h5>
                                    <p><strong>🏥 القسم:</strong> {{ $visit->department->name }}</p>
                                    <p><strong>📅 التاريخ:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت البداية:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت النهاية:</strong> {{ $visit->appointment->appointment_end_time }}</p>
                                    <p><strong>📝 التشخيص:</strong> {{ $visit->diagnosis ?? '-' }}</p>
                                    <p><strong>📌 الملاحظات:</strong> {{ $visit->notes ?? '-' }}</p>
                                    <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-primary">
                                        عرض التفاصيل
                                    </a>
                                </div>
                                @if($visit->status == 5)
                                        <div class="card-footer text-muted bg-danger">
                                            <small>تم انهاء الزيارة</small>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $visits->links() }}
                </div>
            @else
                <div class="alert alert-info text-center fs-5 py-3">
                    لا توجد طلبات عمليات.
                </div>
            @endif
        </div>

        <style>
            .visit-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .visit-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            .card-body p {
                font-size: 0.95rem;
            }
        </style>
    @elseif(auth()->user()->hasRole('ممرض الجناح'))
        <div class="container">
            <h2 class="fw-bold text-center mb-1">📋 طلبات العناية التمريضية</h2>
            @if($prescriptions_visits->count() > 0)
                <div class="row g-4">
                    @foreach($prescriptions_visits as $visit)
                        <div class="col-md-4">
                            <div
                                class="card shadow-sm rounded-4 h-80 visit-card
                                                                                                                                                                                                                                                                                        @if($visit->appointment->appointment_start_time < now()) border-secondary
                                                                                                                                                                                                                                                                                        @else border-primary @endif">
                                <div
                                    class="card-header text-white
                                                                                                                                                                                                                                                                                            @if($visit->appointment->appointment_start_time < now()) bg-secondary
                                                                                                                                                                                                                                                                                            @else bg-primary @endif
                                                                                                                                                                                                                                                                                            rounded-top-4 text-center">
                                    {{ $visit->appointment->appointment_start_time < now() ? 'منتهي' : 'نشط' }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mt-1 fw-bold">اسم المريض: {{ $visit->patient->first_name }}
                                        {{ $visit->patient->last_name }}
                                    </h5>
                                    <p><strong>🏥 القسم:</strong> {{ $visit->department->name }}</p>
                                    <p><strong>📅 التاريخ:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت البداية:</strong> {{ $visit->appointment->appointment_start_time }}</p>
                                    <p><strong>🕒 وقت النهاية:</strong> {{ $visit->appointment->appointment_end_time }}</p>
                                    <p><strong>📝 التشخيص:</strong> {{ $visit->diagnosis ?? '-' }}</p>
                                    <p><strong>📌 الملاحظات:</strong> {{ $visit->notes ?? '-' }}</p>
                                    <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-primary">
                                        عرض التفاصيل
                                    </a>
                                </div>
                                @if($visit->status == 5)
                                        <div class="card-footer text-muted bg-danger">
                                            <small>تم انهاء الزيارة</small>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $visits->links() }}
                </div>
            @else
                <div class="alert alert-info text-center fs-5 py-3">
                    لا توجد طلبات وصفات طبية.
                </div>
            @endif
        </div>

        <style>
            .visit-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .visit-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            .card-body p {
                font-size: 0.95rem;
            }
        </style>
    @endif

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bookModal = document.getElementById('bookModal');
            bookModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var appointmentId = button.getAttribute('data-id');
                var doctorName = button.getAttribute('data-doctor');
                var departmentName = button.getAttribute('data-department');
                var startTime = button.getAttribute('data-start');
                var endTime = button.getAttribute('data-end');

                document.getElementById('modalAppointmentId').value = appointmentId;
                document.getElementById('modalDoctor').textContent = doctorName;
                document.getElementById('modalDepartment').textContent = departmentName;
                document.getElementById('modalStartTime').textContent = startTime;
                document.getElementById('modalEndTime').textContent = endTime;
            });
        });
    </script>
@endsection