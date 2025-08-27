@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>الحجوزات</h2>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary mb-3">إضافة حجز جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>الموعد</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reservation->patient->name ?? '-' }}</td>
                        <td>{{ $reservation->appointment->appointment_time ?? '-' }}</td>
                        <td>{{ ucfirst($reservation->status) }}</td>
                        <td>
                            <a href="{{ route('reservations.edit', $reservation->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
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