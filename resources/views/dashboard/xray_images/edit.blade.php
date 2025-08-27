@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل صورة الأشعة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('xray_images.update', $xRayImage->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>المريض</label>
                <select name="patient_id" class="form-control">
                    <option value="">اختر المريض</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($xRayImage->patient_id == $patient->id) selected @endif>
                            {{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>رابط الصورة</label>
                <input type="text" name="image_path" class="form-control" value="{{ $xRayImage->image_path }}">
            </div>

            <div class="mb-3">
                <label>تقرير فني</label>
                <textarea name="technical_report" class="form-control">{{ $xRayImage->technical_report }}</textarea>
            </div>

            <div class="mb-3">
                <label>اسم الفني</label>
                <input type="text" name="technician_name" class="form-control" value="{{ $xRayImage->technician_name }}">
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection