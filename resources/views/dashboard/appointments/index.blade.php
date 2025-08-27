@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>المواعيد</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">إضافة موعد جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الدكتور</th>
                    <th>القسم</th>
                    <th>البداية</th>
                    <th>النهاية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $appointment->doctor->first_name ?? '-' }} {{ $appointment->doctor->last_name ?? '-' }}</td>
                        <td>{{ $appointment->department->name ?? '-' }}</td>
                        <td>{{ $appointment->appointment_start_time }}</td>
                        <td>{{ $appointment->appointment_end_time }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                        <td>
                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection