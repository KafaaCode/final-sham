@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>صور الأشعة</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>الصورة</th>
                    <th>تقرير فني</th>
                    <th>الفني</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($xRayImages as $xray)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $xray->patient->name ?? '-' }}</td>
                        <td>{{ $xray->image_path }}</td>
                        <td>{{ $xray->technical_report ?? '-' }}</td>
                        <td>{{ $xray->technician_name }}</td>
                        <td>
                            <a href="{{ route('xrays.edit', $xray->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('xrays.destroy', $xray->id) }}" method="POST"
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