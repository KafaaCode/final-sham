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

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label>الدكتور</label>
                <select name="doctor_id" class="form-control">
                    <option value="">اختر الدكتور</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-1">
                <label>القسم</label>
                <select name="department_id" class="form-control">
                    <option value="">اختر القسم</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-1">
                <label>البداية</label>
                <input type="datetime-local" name="appointment_start_time" class="form-control">
            </div>

            <div class="mb-1">
                <label>النهاية</label>
                <input type="datetime-local" name="appointment_end_time" class="form-control">
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection