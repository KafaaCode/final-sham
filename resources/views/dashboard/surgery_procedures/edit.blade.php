@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل الإجراء</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('surgery_procedures.update', $surgeryProcedure->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>العملية</label>
                <select name="surgery_id" class="form-control">
                    <option value="">اختر العملية</option>
                    @foreach($surgeries as $surgery)
                        <option value="{{ $surgery->id }}" @if($surgeryProcedure->surgery_id == $surgery->id) selected @endif>
                            {{ $surgery->surgery_type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>نوع الإجراء</label>
                <input type="text" name="procedure_type" class="form-control"
                    value="{{ $surgeryProcedure->procedure_type }}">
            </div>

            <div class="mb-3">
                <label>الأجهزة</label>
                <input type="text" name="equipment" class="form-control" value="{{ $surgeryProcedure->equipment }}">
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection