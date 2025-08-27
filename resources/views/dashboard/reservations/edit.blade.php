@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل الحجز</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>الموعد</label>
                <select name="appointment_id" class="form-control">
                    <option value="">اختر الموعد</option>
                    @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}" @if($reservation->appointment_id == $appointment->id) selected
                        @endif>
                            دكتور: {{ $appointment->doctor->name ?? '-' }} - {{ $appointment->appointment_time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($reservation->patient_id == $patient->id) selected @endif>
                            {{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending" @if($reservation->status == 'pending') selected @endif>قيد الانتظار</option>
                    <option value="confirmed" @if($reservation->status == 'confirmed') selected @endif>مؤكد</option>
                    <option value="cancelled" @if($reservation->status == 'cancelled') selected @endif>ملغى</option>
                </select>
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection