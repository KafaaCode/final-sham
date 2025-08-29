@extends('layouts.app')

@section('content')
<div class="container">
    <h3>طلبات الرعاية التمريضية</h3>
    <table class="table">
        <thead>
            <tr>
                <th>المريض</th>
                <th>الدكتور</th>
                <th>الرسالة</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->patient->first_name ?? '' }} {{ $request->patient->last_name ?? '' }}</td>
                    <td>{{ $request->doctor->first_name ?? '' }} {{ $request->doctor->last_name ?? '' }}</td>
                    <td>{{ $request->message }}</td>
                    <td>{{ $request->status }}</td>
                    <td>
                        @if(auth()->user()->hasRole('ممرض الجناح') && $request->status == 'pending')
                            <form method="POST" action="{{ route('nursing_requests.accept', $request->id) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">قبول</button>
                            </form>
                        @endif
                        @if(auth()->user()->hasRole('ممرض الجناح') && $request->status == 'accepted' && $request->nurse_id == auth()->id())
                            <form method="POST" action="{{ route('nursing_requests.complete', $request->id) }}">
                                @csrf
                                <input type="text" name="notes" placeholder="ملاحظات الإجراء" required>
                                <button class="btn btn-primary btn-sm">تسجيل إجراء</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
