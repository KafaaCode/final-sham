@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تحاليل المختبر</h2>
        <a href="{{ route('lab_tests.create') }}" class="btn btn-primary mb-3">إضافة تحليل جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>النتيجة</th>
                    <th>التقرير الفني</th>
                    <th>اسم الفني</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labTests as $labTest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $labTest->patient->name ?? '-' }}</td>
                        <td>{{ $labTest->result }}</td>
                        <td>{{ $labTest->technical_report ?? '-' }}</td>
                        <td>{{ $labTest->technician_name }}</td>
                        <td>
                            <a href="{{ route('lab_tests.edit', $labTest->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('lab_tests.destroy', $labTest->id) }}" method="POST"
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