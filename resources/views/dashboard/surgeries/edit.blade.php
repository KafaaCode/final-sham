@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل العملية</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('surgeries.update', $surgery->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>الدكتور</label>
                <select name="doctor_id" class="form-control">
                    <option value="">اختر الدكتور</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @if($surgery->doctor_id == $doctor->id) selected @endif>
                            {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($surgery->patient_id == $patient->id) selected @endif>
                            {{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>نوع العملية</label>
                <input type="text" name="surgery_type" class="form-control" value="{{ $surgery->surgery_type }}">
            </div>

            <div class="mb-3">
                <label>وقت البداية</label>
                <input type="datetime-local" name="start_time" class="form-control"
                    value="{{ \Carbon\Carbon::parse($surgery->start_time)->format('Y-m-d\TH:i') }}">
            </div>

            <div class="mb-3">
                <label>وقت النهاية</label>
                <input type="datetime-local" name="end_time" class="form-control"
                    value="{{ $surgery->end_time ? \Carbon\Carbon::parse($surgery->end_time)->format('Y-m-d\TH:i') : '' }}">
            </div>

            <div class="mb-3">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control">{{ $surgery->notes }}</textarea>
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection