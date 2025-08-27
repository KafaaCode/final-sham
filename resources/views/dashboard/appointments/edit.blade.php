@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل الموعد</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>الدكتور</label>
                <select name="doctor_id" class="form-control">
                    <option value="">اختر الدكتور</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @if($appointment->doctor_id == $doctor->id) selected @endif>
                            {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>القسم</label>
                <select name="department_id" class="form-control">
                    <option value="">اختر القسم</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @if($appointment->department_id == $department->id) selected @endif>
                            {{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>وقت الموعد</label>
                <input type="datetime-local" name="appointment_time" class="form-control"
                    value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="mb-3">
                <label>الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending" @if($appointment->status == 'pending') selected @endif>قيد الانتظار</option>
                    <option value="confirmed" @if($appointment->status == 'confirmed') selected @endif>مؤكد</option>
                    <option value="cancelled" @if($appointment->status == 'cancelled') selected @endif>ملغى</option>
                </select>
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection