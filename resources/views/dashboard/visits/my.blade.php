@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>زياراتي</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($visits->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>القسم</th>
                        <th>الموعد</th>
                        <th>التشخيص</th>
                        <th>ملاحظات</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th> <!-- عمود جديد -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($visits as $visit)
                        <tr>
                            <td>{{ $visit->department->name }}</td>
                            <td>
                                {{ optional($visit->appointment)->appointment_start_time }}
                                -
                                {{ optional($visit->appointment)->appointment_end_time }}
                            </td>
                            <td>{{ $visit->diagnosis ?? '-' }}</td>
                            <td>{{ $visit->notes ?? '-' }}</td>
                            <td>{{ $visit->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <!-- زر الانتقال لصفحة تفاصيل الزيارة -->
                                <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-primary">
                                    عرض التفاصيل
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $visits->links() }}
        @else
            <div class="alert alert-info">لا توجد زيارات مسجلة.</div>
        @endif
    </div>
@endsection