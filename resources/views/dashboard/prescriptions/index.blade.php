@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>الوصفات الطبية</h2>
        <!-- <a href="{{ route('prescriptions.create') }}" class="btn btn-primary mb-3">إضافة وصفة جديدة</a> -->

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>اسم الدواء</th>
                    <th>الجرعة</th>
                    <th>المدة</th>
                    <th>ملاحظات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prescription->patient->name ?? '-' }}</td>
                        <td>{{ $prescription->medicine_name }}</td>
                        <td>{{ $prescription->dosage }}</td>
                        <td>{{ $prescription->duration }}</td>
                        <td>{{ $prescription->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('prescriptions.edit', $prescription->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST"
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