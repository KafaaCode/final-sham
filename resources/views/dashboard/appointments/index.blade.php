@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>المواعيد</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">إضافة موعد جديد</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الدكتور</th>
                    <th>القسم</th>
                    <th>البداية</th>
                    <th>النهاية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $appointment->doctor->first_name ?? '-' }} {{ $appointment->doctor->last_name ?? '-' }}</td>
                        <td>{{ $appointment->department->name ?? '-' }}</td>
                        <td>{{ $appointment->appointment_start_time }}</td>
                        <td>{{ $appointment->appointment_end_time }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                        <td>
                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                            <!-- زر إلغاء الموعد -->
                            @if($appointment->status != 'ملغي')
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#cancelModal" data-id="{{ $appointment->id }}">
                                    إلغاء
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal إدخال سبب الإلغاء -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="cancelForm" method="POST" action="">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-secondary text-white">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-danger">تأكيد الإلغاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var cancelModal = document.getElementById('cancelModal');
                cancelModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var form = document.getElementById('cancelForm');
                    form.action = '/appointments/' + id + '/cancel';
                });
            });
        </script>
    </div>
@endsection