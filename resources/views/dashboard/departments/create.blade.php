@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إضافة قسم جديد</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>اسم القسم</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>الوصف</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection