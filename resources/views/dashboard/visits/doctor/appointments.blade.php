@extends('layouts.app')

@section('title', 'مواعيدي')

@section('content')
    <div class="container">
        <h2 class="mb-1 text-center fw-bold text-primary">📅 مواعيدي</h2>

        @if($appointments->isEmpty())
            <div class="alert alert-warning text-center">
                لا يوجد مواعيد مسجلة حاليا ✅
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle shadow-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>🏥 القسم</th>
                            <th>⏰ وقت البداية</th>
                            <th>⏰ وقت النهاية</th>
                            <th>📅 التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->department->name ?? 'غير محدد' }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_end_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_start_time)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection