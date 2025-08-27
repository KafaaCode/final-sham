@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة وصفة جديدة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf
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
                <label>اسم الدواء</label>
                <input type="text" name="medicine_name" class="form-control">
            </div>

            <div class="mb-3">
                <label>الجرعة</label>
                <input type="text" name="dosage" class="form-control">
            </div>

            <div class="mb-3">
                <label>المدة</label>
                <input type="text" name="duration" class="form-control">
            </div>

            <div class="mb-3">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection