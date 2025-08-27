@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تعديل القسم</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>اسم القسم</label>
                <input type="text" name="name" class="form-control" value="{{ $department->name }}">
            </div>

            <div class="mb-3">
                <label>الوصف</label>
                <textarea name="description" class="form-control">{{ $department->description }}</textarea>
            </div>

            <button class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection