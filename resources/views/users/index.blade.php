@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h2 class="fw-bold">قائمة المستخدمين</h2>
            <a href="{{ url('/user-create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> إضافة مستخدم
            </a>
        </div>

        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>الاسم الاول</th>
                        <th>الاسم الثاني</th>
                        <th>اسم الاب</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحيات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->first_name }}</td>
                            <td class="fw-semibold">{{ $user->last_name }}</td>
                            <td class="fw-semibold">{{ $user->father_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="badge bg-info text-dark px-2 py-1">
                                            @if ($v == 'Super Admin')
                                                المدير
                                            @else
                                                {{ $v }}
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">لا يوجد صلاحية</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">لا يوجد مستخدمين حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SweetAlert تأكيد الحذف --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection