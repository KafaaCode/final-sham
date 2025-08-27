@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة عملية جديدة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('surgeries.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>الدكتور</label>
                <select name="doctor_id" class="form-control">
                    <option value="">اختر الدكتور</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
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
                <label>نوع العملية</label>
                <input type="text" name="surgery_type" class="form-control">
            </div>

            <div class="mb-3">
                <label>وقت البداية</label>
                <input type="datetime-local" name="start_time" class="form-control">
            </div>

            <div class="mb-3">
                <label>وقت النهاية</label>
                <input type="datetime-local" name="end_time" class="form-control">
            </div>

            <div class="mb-3">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection