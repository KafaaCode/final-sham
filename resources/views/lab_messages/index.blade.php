@extends('layouts.app')

@section('title', 'رسائل المخبر')

@section('content')
<div class="container py-4">
    <div class="mb-4 text-center">
        <h2 class="fw-bold text-warning">🧪 طلبات التحليل المخبري</h2>
        <p class="text-muted">عرض جميع طلبات التحليل المخبري</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- قائمة الرسائل -->
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4">
            <h5 class="mb-0">📨 طلبات التحليل المخبري</h5>
        </div>
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="list-group">
                    @foreach($messages as $message)
                        <div class="list-group-item border-warning mb-2 rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-warning">طلب تحليل مخبري</h6>
                                <span class="badge bg-{{ $message->status == 'مكتمل' ? 'success' : 'secondary' }}">
                                    {{ $message->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>👨‍⚕️ المريض:</strong> {{ $message->patient->name ?? 'غير محدد' }}</p>
                                    <p><strong>📝 الرسالة:</strong> {{ $message->message ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>📅 التاريخ:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                                    <p><strong>📊 الحالة:</strong> 
                                        <span class="badge bg-{{ $message->status == 'مكتمل' ? 'success' : 'secondary' }}">
                                            {{ $message->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- أزرار الإجراءات -->
                            <div class="mt-3">
                                @if($message->status != 'مكتمل')
                                    <button class="btn btn-success btn-sm" onclick="markAsCompleted({{ $message->id }})">
                                        <i class="fas fa-check"></i> تم الإنجاز
                                    </button>
                                @else
                                    <span class="text-success fw-bold">✅ تم إنجاز هذا الطلب</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد طلبات تحليل مخبري</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// دالة تغيير الحالة إلى مكتمل
function markAsCompleted(messageId) {
    Swal.fire({
        title: 'تأكيد الإنجاز',
        text: 'هل تريد تأكيد إنجاز هذا التحليل؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، تم الإنجاز',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // إرسال طلب AJAX لتحديث الحالة
            fetch(`/messages/lab/${messageId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'تم الإنجاز بنجاح!',
                        text: 'تم تحديث حالة الرسالة إلى مكتملة',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء تحديث الحالة',
                        icon: 'error',
                        confirmButtonText: 'حسناً'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ في الاتصال',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            });
        }
    });
}
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.list-group-item {
    transition: all 0.3s ease;
}

.list-group-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.75em;
    padding: 0.5em 0.8em;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}
</style>
@endsection
