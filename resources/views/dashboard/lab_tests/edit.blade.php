@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل التحليل</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('lab_tests.update', $labTest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($labTest->patient_id == $patient->id) selected @endif>
                            {{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>النتيجة</label>
                <input type="text" name="result" class="form-control" value="{{ $labTest->result }}">
            </div>

            <div class="mb-3">
                <label>التقرير الفني</label>
                <textarea name="technical_report" class="form-control">{{ $labTest->technical_report }}</textarea>
            </div>

            <div class="mb-3">
                <label>اسم الفني</label>
                <input type="text" name="technician_name" class="form-control" value="{{ $labTest->technician_name }}">
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection