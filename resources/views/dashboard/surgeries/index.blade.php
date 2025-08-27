@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-1">⚕️ العمليات</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الدكتور</th>
                    <th>المريض</th>
                    <th>نوع العملية</th>
                    <th>وقت البداية</th>
                    <th>وقت النهاية</th>
                    <th>ملاحظات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surgeries as $surgery)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $surgery->doctor->first_name ?? '-' }} {{ $surgery->doctor->last_name ?? '-' }}</td>
                        <td>{{ $surgery->patient->first_name ?? '-' }} {{ $surgery->patient->last_name ?? '-' }}</td>
                        <td>{{ $surgery->surgery_type }}</td>
                        <td>{{ $surgery->start_time }}</td>
                        <td>{{ $surgery->end_time ?? '-' }}</td>
                        <td>{{ $surgery->notes ?? '-' }}</td>
                        <td>
                            <!-- زر عرض التفاصيل -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#procedureDetailsModal{{ $surgery->id }}">
                                عرض التفاصيل
                            </button>

                            <!-- المودال -->
                            <div class="modal fade" id="procedureDetailsModal{{ $surgery->id }}" tabindex="-1"
                                aria-labelledby="procedureDetailsLabel{{ $surgery->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="procedureDetailsLabel{{ $surgery->id }}">
                                                تفاصيل العملية
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="إغلاق"></button>
                                        </div>

                                        <div class="modal-body">
                                            @if($surgery->procedures->count() > 0)
                                                @foreach($surgery->procedures as $procedure)
                                                    <div class="border rounded p-2 mb-2">
                                                        <p><strong>نوع الإجراء:</strong> {{ $procedure->procedure_type }}</p>
                                                        <p><strong>المعدات:</strong> {{ $procedure->equipment }}</p>
                                                        <p><strong>رقم العملية:</strong> {{ $procedure->surgery_id }}</p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">لا توجد تفاصيل إجراءات لهذه العملية.</p>
                                            @endif
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">إغلاق</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">لا توجد عمليات مسجلة حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection