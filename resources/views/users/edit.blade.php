@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="fw-bold mb-4">تعديل بيانات المستخدم</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>حدثت بعض الأخطاء:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="shadow p-4 rounded bg-white">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم الأول</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                        class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم الأخير</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم الأب</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $user->father_name) }}"
                        class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم الأم</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $user->mother_name) }}"
                        class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select">
                        <option value="">اختر</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                        class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">العنوان الحالي</label>
                    <input type="text" name="current_address" value="{{ old('current_address', $user->current_address) }}"
                        class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}"
                        class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">مكان الولادة</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $user->place_of_birth) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">كلمة المرور (اتركها فارغة إذا لا تريد التغيير)</label>
                    <input type="password" name="password" class="form-control" placeholder="********">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="********">
                </div>

                {{-- الصلاحيات --}}
                <div class="col-12 mb-3">
                    <label class="form-label">الصلاحيات</label>
                    <div class="row">
                        @foreach ($allRoles as $roleName => $roleLabel)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="checkbox" name="roles[]" value="{{ $roleName }}" class="form-check-input"
                                        id="role-{{ $loop->index }}" {{ in_array($roleName, $userRole) ? 'checked' : '' }}>
                                    <label for="role-{{ $loop->index }}" class="form-check-label">{{ $roleLabel }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">تحديث البيانات</button>
            </div>
        </form>
    </div>
@endsection