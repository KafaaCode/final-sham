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
                                @if(optional($visit->appointment)->status != 'ملغي')
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelVisitModal" data-id="{{ $visit->id }}">إلغاء الموعد</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal إلغاء الموعد -->
            <div class="modal fade" id="cancelVisitModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="cancelVisitForm" method="POST" action="">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">إلغاء الموعد</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">سبب الإلغاء (اختياري)</label>
                                    <textarea name="reason" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-danger">تأكيد الإلغاء</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var cancelModal = document.getElementById('cancelVisitModal');
                    cancelModal.addEventListener('show.bs.modal', function (event) {
                        var button = event.relatedTarget;
                        var id = button.getAttribute('data-id');
                        var form = document.getElementById('cancelVisitForm');
                        form.action = '/visits/' + id + '/cancel';
                    });
                });
            </script>

            {{ $visits->links() }}
        @else
            <div class="alert alert-info">لا توجد زيارات مسجلة.</div>
        @endif
    </div>
@endsection