@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header" style=" background-color: #00a102; ">
                        <h4 class="mb-0 text-white">الملف الشخصي</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-1">
                                <label class="form-label">الاسم الاول</label>
                                <input type="text" name="first_name" class="form-control"
                                    value="{{ old('first_name', Auth::user()->first_name) }}" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label">الاسم الثاني</label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ old('last_name', Auth::user()->last_name) }}" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label">اسم الام</label>
                                <input type="text" name="mother_name" class="form-control"
                                    value="{{ old('mother_name', Auth::user()->mother_name) }}" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label">اسم الاب</label>
                                <input type="text" name="father_name" class="form-control"
                                    value="{{ old('father_name', Auth::user()->father_name) }}" required>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', Auth::user()->email) }}" required>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', Auth::user()->phone) }}">
                            </div>

                            <div class="mb-1">
                                <label class="form-label">الرقم الوطني</label>
                                <input type="text" name="national_id" class="form-control"
                                    value="{{ old('national_id', Auth::user()->national_id) }}">
                            </div>

                            <div class="mb-1">
                                <label class="form-label">العنوان</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', Auth::user()->address) }}">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">حفظ التعديلات</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection