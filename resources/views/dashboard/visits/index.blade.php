@extends('layouts.app')

@section('title', 'سجل الزيارات')

@section('content')
    <div class="container">

        <h2 class="fw-bold text-center">📋 سجل الزيارات</h2>

        <!-- فلترة بالتاريخ -->
        <form method="GET" action="{{ route('visits.index') }}" class="mb-1 row g-3 align-items-end justify-content-center">
            <div class="col-md-4">
                <label for="date" class="form-label fw-bold">اختر التاريخ</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}">

            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">🔍 فلترة</button>
            </div>
        </form>

        @if($visits->count() > 0)
            <div class="row g-4">
                @foreach($visits as $visit)
                    <div class="col-md-4">
                        <div class="card shadow-sm rounded-4 h-100 visit-card
                                                                        @if($visit->status == 'booked') border-success
                                                                        @elseif($visit->visit_date < now()->toDateString()) border-secondary
                                                                        @else border-warning @endif">
                            <div class="card-header
                                                                        @if($visit->status == 'booked') bg-success text-white
                                                                        @elseif($visit->visit_date < now()->toDateString()) bg-secondary text-white
                                                                        @else bg-warning text-dark @endif
                                                                        rounded-top-4 text-center">
                                <strong>
                                    المريض: {{ $visit->patient->first_name }}
                                    {{ $visit->patient->last_name }}
                                </strong>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-primary">{{ $visit->patient_name }}</h5>
                                <p><strong>👨‍⚕️ الطبيب:</strong> {{ $visit->appointment->doctor->first_name }}
                                    {{ $visit->appointment->doctor->last_name }}
                                </p>
                                <p><strong>👨‍⚕️المريض:</strong> {{ $visit->patient->first_name }}
                                    {{ $visit->patient->last_name }}
                                </p>
                                <p><strong>🏥 القسم:</strong> {{  $visit->department->name  }}</p>
                                <p><strong>📅 التاريخ:</strong> {{ $visit->created_at->format('Y-m-d H:i') }}</p>
                                <p><strong>🕒 وقت البداية:</strong>
                                    {{ optional($visit->appointment)->appointment_start_time }}</p>
                                <p><strong>🕒 وقت النهاية:</strong>
                                    {{ optional($visit->appointment)->appointment_end_time }}</p>
                                <p><strong style="font-weight: bold;">التشخيص:</strong>
                                    {{ $visit->diagnosis ?? '-' }}</p>
                                <p><strong style="font-weight: bold;">ملاحظات الطبيب:</strong>
                                    {{ $visit->notes ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center py-3 mt-4 fs-5">
                ⚠️ لا توجد زيارات في التاريخ المحدد
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

        .card-header {
            font-size: 1.1rem;
        }

        .card-body p {
            font-size: 0.95rem;
        }
    </style>
@endsection