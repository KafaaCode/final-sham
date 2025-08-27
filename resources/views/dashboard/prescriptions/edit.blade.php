@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل الوصفة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($prescription->patient_id == $patient->id) selected @endif>
                            {{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>اسم الدواء</label>
                <input type="text" name="medicine_name" class="form-control" value="{{ $prescription->medicine_name }}">
            </div>

            <div class="mb-3">
                <label>الجرعة</label>
                <input type="text" name="dosage" class="form-control" value="{{ $prescription->dosage }}">
            </div>

            <div class="mb-3">
                <label>المدة</label>
                <input type="text" name="duration" class="form-control" value="{{ $prescription->duration }}">
            </div>

            <div class="mb-3">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control">{{ $prescription->notes }}</textarea>
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection