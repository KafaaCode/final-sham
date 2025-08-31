@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>إجراءات العمليات</h2>
        <!-- <a href="{{ route('surgery_procedures.create') }}" class="btn btn-primary mb-3">إضافة إجراء جديد</a> -->

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العملية</th>
                    <th>نوع الإجراء</th>
                    <th>الأجهزة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($procedures as $procedure)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $procedure->surgery->surgery_type ?? '-' }}</td>
                        <td>{{ $procedure->procedure_type }}</td>
                        <td>{{ $procedure->equipment ?? '-' }}</td>
                        <td>
                            <a href="{{ route('surgery_procedures.edit', $procedure->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('surgery_procedures.destroy', $procedure->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection