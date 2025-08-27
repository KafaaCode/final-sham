@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة صورة أشعة جديدة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('xray_images.store') }}" method="POST">
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
                <label>رابط الصورة</label>
                <input type="text" name="image_path" class="form-control">
            </div>

            <div class="mb-3">
                <label>تقرير فني</label>
                <textarea name="technical_report" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>اسم الفني</label>
                <input type="text" name="technician_name" class="form-control">
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection