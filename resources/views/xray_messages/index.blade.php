@extends('layouts.app')

@section('title', 'رسائل الأشعة')

@section('content')
<div class="container">
    <div class="mb-3 text-center">
        <h2 class="fw-bold text-info">🖼️ رسائل الأشعة</h2>
        <p class="text-muted">عرض جميع رسائل الأشعة المرسلة</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h5 class="mb-0">📨 الرسائل المرسلة</h5>
        </div>
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="list-group">
                    @foreach($messages as $message)
                        <div class="list-group-item border-info mb-2 rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-info">{{ $message->examination_type }}</h6>
                                <span class="badge bg-{{ $message->priority == 'طارئة' ? 'danger' : ($message->priority == 'عاجلة' ? 'warning' : 'secondary') }}">
                                    {{ $message->priority }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>👨‍⚕️ المريض:</strong> {{ $message->patient->name ?? 'غير محدد' }}</p>
                                    <p><strong>📋 التفاصيل:</strong> {{ $message->examination_details ?? '-' }}</p>
                                    <p><strong>💊 معلومات طبية:</strong> {{ $message->medical_info ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>📝 الرسالة:</strong> {{ $message->message ?? '-' }}</p>
                                    <p><strong>📅 التاريخ:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                                    <p><strong>📊 الحالة:</strong> 
                                        <span class="badge bg-{{ $message->status == 'مكتمل' ? 'success' : ($message->status == 'قيد التنفيذ' ? 'warning' : 'secondary') }}">
                                            {{ $message->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center">لا توجد رسائل مرسلة</p>
            @endif
        </div>
    </div>
</div>
@endsection
