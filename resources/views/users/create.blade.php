@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- رسائل الأخطاء --}}
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

        {{-- رسالة نجاح --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="shadow p-2 rounded bg-white">
            @csrf

            <div class="row">
                <h2 class="fw-bold mb-1">إضافة مستخدم جديد</h2>

                {{-- الاسم الأول --}}
                <div class="col-md-4 mb-1">
                    <label for="first_name" class="form-label">الاسم الأول</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                        class="form-control" required>
                </div>

                {{-- الاسم الأخير --}}
                <div class="col-md-4 mb-1">
                    <label for="last_name" class="form-label">الاسم الأخير</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control"
                        required>
                </div>

                {{-- اسم الأب --}}
                <div class="col-md-4 mb-1">
                    <label for="father_name" class="form-label">اسم الأب</label>
                    <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}"
                        class="form-control">
                </div>

                {{-- اسم الأم --}}
                <div class="col-md-4 mb-1">
                    <label for="mother_name" class="form-label">اسم الأم</label>
                    <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}"
                        class="form-control">
                </div>

                {{-- الجنس --}}
                <div class="col-md-4 mb-1">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select">
                        <option value="">اختر</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                {{-- الهاتف --}}
                <div class="col-md-4 mb-1">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>

                {{-- تاريخ الميلاد --}}
                <div class="col-md-4 mb-1">
                    <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                        class="form-control">
                </div>

                {{-- العنوان الحالي --}}
                <div class="col-md-4 mb-1">
                    <label for="current_address" class="form-label">العنوان الحالي</label>
                    <input type="text" id="current_address" name="current_address" value="{{ old('current_address') }}"
                        class="form-control">
                </div>

                {{-- الرقم الوطني --}}
                <div class="col-md-4 mb-1">
                    <label for="national_id" class="form-label">الرقم الوطني</label>
                    <input type="number" id="national_id" name="national_id" value="{{ old('national_id') }}"
                        class="form-control">
                </div>

                {{--  مكان الولادة --}}
                <div class="col-md-4 mb-1">
                    <label for="place_of_birth" class="form-label">مكان الولادة</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" class="form-control">
                </div>

                {{-- البريد الإلكتروني --}}
                <div class="col-md-4 mb-1">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                {{-- كلمة المرور --}}
                <div class="col-md-4 mb-1">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="********"
                        required>
                </div>

                {{-- تأكيد كلمة المرور --}}
                <div class="col-md-4 mb-1">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        placeholder="********" required>
                </div>

                {{-- الصلاحيات --}}
                <div class="col-8 mb-1">
                    <label class="form-label">الصلاحيات</label>
                    <select name="roles[]" class="form-select">
                        @foreach ($allRoles as $roleName => $roleLabel)
                            <option value="{{ $roleName }}">
                                @if ($roleLabel == 'Super Admin')
                                    المدير
                                @else
                                    {{ $roleLabel }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-center mt-1">
                <button type="submit" class="btn btn-success px-4">إضافة المستخدم</button>
            </div>
        </form>
    </div>
@endsection