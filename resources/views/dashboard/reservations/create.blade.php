@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة حجز جديد</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>الموعد</label>
                <select name="appointment_id" class="form-control">
                    <option value="">اختر الموعد</option>
                    @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}">دكتور: {{ $appointment->doctor->name ?? '-' }} -
                            {{ $appointment->appointment_time }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending">قيد الانتظار</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="cancelled">ملغى</option>
                </select>
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection