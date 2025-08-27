@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h2 class="fw-bold">قائمة المسميات الوظيفية</h2>
            <!-- <a href="{{ route('roles.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> إضافة صلاحية
                        </a> -->
        </div>

        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">الصلاحية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td class="fw-semibold" style="font-weight: bold;">
                                @if ($role->name == 'Super Admin')
                                    المدير
                                @else
                                    {{ $role->name }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-muted">لا توجد صلاحيات مسجلة حالياً</td>
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