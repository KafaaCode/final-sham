@extends('layouts.app')

@section('title', 'تفاصيل الزيارة')

@section('content')
    <div class="container">
        <div class="mb-1 text-center">
            <h2 class="fw-bold text-primary">📋 تفاصيل الزيارة</h2>
            <p class="text-muted">عرض جميع بيانات الزيارة للمريض</p>
            <div class="text-end mb-2">
                <a href="{{ route('visits.exportPDF', $visit->id) }}" class="btn btn-success">
                    📄 تصدير تقرير PDF
                </a>
            </div>
        </div>
        <div class="mb-1 text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-1">⬅️ العودة لسجل الزيارات</a>
            @if(optional($visit->appointment)->status != 'ملغي' && (auth()->user()->hasRole('الدكتور') || auth()->user()->hasRole('المريض')))
                <button class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#cancelVisitModal">إلغاء الموعد</button>
            @endif
        </div>
        @if(session('success'))
            <div class="alert alert-success p-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger p-2">{{ session('error') }}</div>
        @endif
        <!-- إحصائيات الرسائل -->
        @if(auth()->user()->hasRole('الدكتور'))
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-info text-white text-center rounded-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">📨 رسائل الأشعة</h6>
                            <h4 class="mb-0">{{ isset($xrayMessages) ? $xrayMessages->count() : 0 }}</h4>
                            <small>رسالة مرسلة</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark text-center rounded-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">🧪 رسائل المخبر</h6>
                            <h4 class="mb-0">{{ isset($labMessages) ? $labMessages->count() : 0 }}</h4>
                            <small>رسالة مرسلة</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white text-center rounded-4 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">🩺 رسائل التمريض</h6>
                            <h4 class="mb-0">{{ isset($nursingRequests) ? $nursingRequests->count() : 0 }}</h4>
                            <small>رسالة مرسلة</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm mb-4 rounded-4">
            <div class="card-header bg-primary rounded-top-4 text-center">
                <h5 class="text-white mb-0 fw-bold">معلومات الزيارة</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 p-2">
                    <div class="col-md-8">
                        <div class="bg-light p-1 rounded shadow-sm h-100">
                            <h6 class="fw-bold text-primary">👨‍⚕️ الطبيب</h6>
                            <p class="mb-2">{{ $visit->appointment->doctor->first_name }}
                                {{ $visit->appointment->doctor->last_name }}
                            </p>
                            <h6 class="fw-bold text-primary">- المريض</h6>
                            <p class="mb-2">{{ $visit->patient->first_name }}
                                {{ $visit->patient->last_name }}
                            </p>
                            <h6 class="fw-bold text-success">🏥 القسم</h6>
                            <p class="mb-2">{{ $visit->department->name }}</p>
                            <h6 class="fw-bold text-danger">📝 التشخيص</h6>
                            <p class="mb-2 diagnosis">{{ $visit->diagnosis ?? '-' }}</p>
                            <h6 class="fw-bold text-secondary">📌 الملاحظات</h6>
                            <p class="mb-0 notes">{{ $visit->notes ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-1 rounded shadow-sm h-100">
                            <h6 class="fw-bold text-warning">📅 التاريخ</h6>
                            <p class="mb-2">{{ $visit->created_at->format('Y-m-d') }}</p>

                            <h6 class="fw-bold text-info">🕒 وقت البداية</h6>
                            <p class="mb-2">{{ optional($visit->appointment)->appointment_start_time }}</p>

                            <h6 class="fw-bold text-info">🕒 وقت النهاية</h6>
                            <p class="mb-0">{{ optional($visit->appointment)->appointment_end_time }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- button for end of visit  butons in line -->
            @if($visit->status != 5 && auth()->user()->hasRole('الدكتور'))
            <div class="text-center mb-1  d-flex justify-content-center gap-2">
                <form action="{{ route('visits.end', $visit->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">إنهاء الزيارة</button>
                </form>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editVisitModal">
                    اضافه التشخيص والملاحظات
                </button>
            </div>
            @endif
        </div>

        <!-- dialog edit diagnosis and notes and script -->
        <div class="modal fade" id="editVisitModal" tabindex="-1" aria-labelledby="editVisitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVisitModalLabel">تعديل التشخيص والملاحظات</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('visits.updateDiagnosisAndNotes', $visit->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="diagnosis" class="form-label">التشخيص</label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3">{{ $visit->diagnosis }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">الملاحظات</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ $visit->notes }}</textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .card-body h6 {
                font-size: 0.95rem;
            }

            .card-body p {
                font-size: 0.9rem;
            }
        </style>

        <style>
            /* تحسين عرض حقول التشخيص والملاحظات */
            .diagnosis, .notes {
                white-space: pre-wrap;
                word-break: break-word;
            }

            .card-body h6 {
                font-size: 0.95rem;
            }

            .card-body p {
                font-size: 0.9rem;
            }
        </style>

        @if(auth()->user()->hasRole('فني الأشعة') || auth()->user()->hasRole('الدكتور'))
            <!-- قسم عرض الرسائل المستلمة لفني الأشعة -->
            @if(auth()->user()->hasRole('فني الأشعة'))
                <div class="card shadow-sm mb-3 rounded-4">
                    <div class="card-header bg-info text-white rounded-top-4">
                        <h5 class="mb-0">📨 الرسائل المستلمة من الأطباء</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($xrayMessages) && $xrayMessages->count() > 0)
                            <div class="list-group">
                                @foreach($xrayMessages as $msg)
                                    <div class="list-group-item border-info">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1 text-info">{{ $msg->examination_type }}</h6>
                                            <span class="badge bg-{{ $msg->priority == 'طارئة' ? 'danger' : ($msg->priority == 'عاجلة' ? 'warning' : 'secondary') }}">
                                                {{ $msg->priority ?? 'عادية' }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>👨‍⚕️ الطبيب:</strong> {{ $msg->doctor->name ?? 'غير محدد' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>📋 التفاصيل:</strong> {{ $msg->examination_details ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>💊 معلومات طبية:</strong> {{ $msg->medical_info ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>📝 رسالة:</strong> {{ $msg->message ?? '-' }}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                                            <button class="btn btn-sm btn-success" onclick="markAsCompleted({{ $msg->id }}, 'xray')">
                                                ✅ تم الإنجاز
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">لا توجد رسائل مستلمة من الأطباء.</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card shadow-sm mb-1 rounded-4">
                <div class="card-header bg-info rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="text-white">🖼️ صور الأشعة</h4>

                    @if(auth()->user()->hasRole('الدكتور') && $visit->status != 5)
                        <button class="btn btn-sm btn-primary" onclick="confirmXray({{ $visit->id }})">
                            طلب صورة
                        </button>
                    @endif

                    @if(auth()->user()->hasRole('فني الأشعة') && $visit->status != 5)
                        <!-- زر فتح النافذة -->
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addXrayModal">
                            ➕ إضافة صورة الأشعة
                        </button>
                    @endif
                </div>

                <div class="card-body">
                    @if($visit->xRayImages->count() > 0)
                        <div class="row g-3">
                            @foreach($visit->xRayImages as $xray)
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        <a href="{{ asset($xray->image_path) }}" target="_blank">
                                            <img src="{{ asset($xray->image_path) }}" class="card-img-top mt-2" alt="صورة أشعة">
                                        </a>
                                        <div class="card-body">
                                            <p><strong>📝 تقرير فني:</strong> {{ $xray->technical_report ?? '-' }}</p>
                                            <p><strong>👨‍🔧 الفني:</strong> {{ $xray->technician_name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mt-1">لا توجد صور أشعة لهذه الزيارة.</p>
                    @endif
                </div>
            </div>

            <!-- نافذة منبثقة لإضافة صورة -->
            <div class="modal fade" id="addXrayModal" tabindex="-1" aria-labelledby="addXrayModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4 shadow-lg">
                        <div class="modal-header bg-info text-white rounded-top-4">
                            <h5 class="modal-title" id="addXrayModalLabel">➕ إضافة صورة أشعة</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="إغلاق"></button>
                        </div>
                        <form action="{{ route('xrays.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                            <div class="modal-body">
                                <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                                <div class="mb-3">
                                    <label class="form-label">📷 اختر صورة الأشعة</label>
                                    <input type="file" name="image_path" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">📝 التقرير الفني</label>
                                    <textarea name="technical_report" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3" style="display: none;">
                                    <label class="form-label">👨‍🔧 اسم الفني</label>
                                    <input type="text" name="technician_name" class="form-control"
                                        value="{{ auth()->user()->name }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ إلغاء</button>
                                <button type="submit" class="btn btn-success">💾 حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if(auth()->user()->hasRole('الدكتور'))
            <!-- بطاقة إرسال طلب رعاية تمريضية -->
            <div class="card shadow-sm mb-3 rounded-4">
                @if($visit->status != 5)
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🩺 إرسال طلب رعاية تمريضية</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#sendNursingRequestModal">
                        ➕ إرسال للممرض
                    </button>
                </div>
                @endif
                </div>
                <div class="card-body">
                    <p class="text-muted">استخدم هذا النموذج لإرسال تعليمات للرعاية التمريضية للمريض المرتبط بهذه الزيارة.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-3 rounded-4">
                <div class="card-header bg-light rounded-top-4">
                    <h5 class="mb-0">سجل الإجراءات التمريضية الأخيرة</h5>
                </div>
                <div class="card-body">
                    @if(isset($nursingActions) && $nursingActions->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($nursingActions as $act)
                                <li class="list-group-item">
                                    <strong>{{ $act->nurse->first_name ?? $act->nurse->name ?? '-' }}</strong>
                                    <div class="text-muted small">{{ $act->created_at->diffForHumans() }}</div>
                                    <div class="mt-1">{{ $act->action }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">لا توجد إجراءات تمريضية مسجلة.</p>
                    @endif
                </div>
            </div>

            <!-- Modal إرسال طلب رعاية تمريضية -->
            <div class="modal fade" id="sendNursingRequestModal" tabindex="-1" aria-labelledby="sendNursingRequestLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="sendNursingRequestLabel">إرسال طلب رعاية تمريضية</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <form action="{{ route('nursing_requests.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">رسالة إلى الممرض</label>
                                    <textarea name="message" class="form-control" rows="4" required placeholder="أدخل الإجراءات أو الملاحظات للممرض..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-success">إرسال الطلب</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->hasRole('الدكتور'))
            <!-- بطاقة إرسال رسالة لفني الأشعة -->
            <div class="card shadow-sm mb-3 rounded-4">
                @if($visit->status != 5)
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🖼️ إرسال رسالة لفني الأشعة</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#sendXrayMessageModal">
                        ➕ إرسال للفني
                    </button>
                </div>
                @endif
                <div class="card-body">
                    <p class="text-muted">استخدم هذا النموذج لإرسال تعليمات وتفاصيل نوع الفحص المطلوب لفني الأشعة.</p>
                    
                    <!-- عرض الرسائل المرسلة لفني الأشعة -->
                    @if(isset($xrayMessages) && $xrayMessages->count() > 0)
                        <div class="mt-3">
                            <h6 class="fw-bold text-info">📨 الرسائل المرسلة:</h6>
                            <div class="list-group">
                                @foreach($xrayMessages as $msg)
                                    <div class="list-group-item border-info">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <!-- <h6 class="mb-1">{{ $msg->examination_type }}</h6> -->
                                                <!-- <p class="mb-1"><strong>التفاصيل:</strong> {{ $msg->examination_details ?? '-' }}</p> -->
                                                <p class="mb-1"><strong>الرسالة:</strong> {{ $msg->message ?? '-' }}</p>
                                                <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                            <span class="badge bg-info">{{ $msg->status ?? 'جديد' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-muted mt-2">لا توجد رسائل مرسلة لفني الأشعة.</p>
                    @endif
                </div>
            </div>

            <!-- Modal إرسال رسالة لفني الأشعة -->
            <div class="modal fade" id="sendXrayMessageModal" tabindex="-1" aria-labelledby="sendXrayMessageLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="sendXrayMessageLabel">إرسال رسالة لفني الأشعة</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <form action="{{ route('xray_messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                            <div class="modal-body">
                                <!-- <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">🔍 نوع الفحص المطلوب</label>
                                        <select name="examination_type" class="form-control" required>
                                            <option value="">اختر نوع الفحص</option>
                                            <optgroup label="أشعة سينية">
                                                <option value="أشعة سينية للصدر">أشعة سينية للصدر</option>
                                                <option value="أشعة سينية للعمود الفقري">أشعة سينية للعمود الفقري</option>
                                                <option value="أشعة سينية للأطراف العلوية">أشعة سينية للأطراف العلوية</option>
                                                <option value="أشعة سينية للأطراف السفلية">أشعة سينية للأطراف السفلية</option>
                                                <option value="أشعة سينية للجمجمة">أشعة سينية للجمجمة</option>
                                                <option value="أشعة سينية للبطن">أشعة سينية للبطن</option>
                                            </optgroup>
                                            <optgroup label="أشعة متقدمة">
                                                <option value="أشعة مقطعية للصدر">أشعة مقطعية للصدر</option>
                                                <option value="أشعة مقطعية للبطن">أشعة مقطعية للبطن</option>
                                                <option value="أشعة مقطعية للدماغ">أشعة مقطعية للدماغ</option>
                                                <option value="رنين مغناطيسي للدماغ">رنين مغناطيسي للدماغ</option>
                                                <option value="رنين مغناطيسي للعمود الفقري">رنين مغناطيسي للعمود الفقري</option>
                                                <option value="رنين مغناطيسي للمفاصل">رنين مغناطيسي للمفاصل</option>
                                            </optgroup>
                                            <optgroup label="أشعة بالموجات">
                                                <option value="أشعة بالموجات فوق الصوتية للبطن">أشعة بالموجات فوق الصوتية للبطن</option>
                                                <option value="أشعة بالموجات فوق الصوتية للقلب">أشعة بالموجات فوق الصوتية للقلب</option>
                                                <option value="أشعة بالموجات فوق الصوتية للحوض">أشعة بالموجات فوق الصوتية للحوض</option>
                                            </optgroup>
                                            <option value="أخرى">أخرى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">⚡ أولوية الفحص</label>
                                        <select name="priority" class="form-control">
                                            <option value="عادية">عادية</option>
                                            <option value="عاجلة">عاجلة</option>
                                            <option value="طارئة">طارئة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">📋 تفاصيل الفحص</label>
                                    <textarea name="examination_details" class="form-control" rows="3" placeholder="أدخل تفاصيل الفحص المطلوب، المنطقة المحددة، السبب..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">💊 معلومات المريض الطبية</label>
                                    <textarea name="medical_info" class="form-control" rows="2" placeholder="أي معلومات طبية مهمة للمريض (حساسية، أمراض مزمنة...)" ></textarea>
                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">📝 رسالة إضافية للفني</label>
                                    <textarea name="message" class="form-control" rows="3" placeholder="أدخل أي تعليمات إضافية أو ملاحظات خاصة للفني..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-info">إرسال الرسالة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- بطاقة إرسال رسالة لفني المخبر -->
            <div class="card shadow-sm mb-3 rounded-4">
                @if($visit->status != 5)
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🧪 إرسال رسالة لفني المخبر</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#sendLabMessageModal">
                        ➕ إرسال للفني
                    </button>
                </div>
                @endif
                <div class="card-body">
                    <p class="text-muted">استخدم هذا النموذج لإرسال تعليمات وتفاصيل نوع التحليل المطلوب لفني المخبر.</p>
                    
                    <!-- عرض الرسائل المرسلة لفني المخبر -->
                    @if(isset($labMessages) && $labMessages->count() > 0)
                        <div class="mt-3">
                            <h6 class="fw-bold text-warning">📨 الرسائل المرسلة:</h6>
                            <div class="list-group">
                                @foreach($labMessages as $msg)
                                    <div class="list-group-item border-warning">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <!-- <h6 class="mb-1">{{ $msg->test_type }}</h6> -->
                                                <!-- <p class="mb-1"><strong>التفاصيل:</strong> {{ $msg->test_details ?? '-' }}</p> -->
                                                <p class="mb-1"><strong>الرسالة:</strong> {{ $msg->message ?? '-' }}</p>
                                                <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                            <span class="badge bg-warning text-dark">{{ $msg->status ?? 'جديد' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-muted mt-2">لا توجد رسائل مرسلة لفني المخبر.</p>
                    @endif
                </div>
            </div>

            <!-- Modal إرسال رسالة لفني المخبر -->
            <div class="modal fade" id="sendLabMessageModal" tabindex="-1" aria-labelledby="sendLabMessageLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="sendLabMessageLabel">إرسال رسالة لفني المخبر</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <form action="{{ route('lab_messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                            <div class="modal-body">
                            
                                <div class="mb-3">
                                    <label class="form-label fw-bold">📝 رسالة إضافية للفني</label>
                                    <textarea name="message" class="form-control" rows="3" placeholder="أدخل أي تعليمات إضافية أو ملاحظات خاصة للفني..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-warning">إرسال الرسالة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->hasRole('فني المخبر') || auth()->user()->hasRole('الدكتور'))
            <!-- قسم عرض الرسائل المستلمة لفني المخبر -->
            @if(auth()->user()->hasRole('فني المخبر'))
                <div class="card shadow-sm mb-3 rounded-4">
                    <div class="card-header bg-warning text-dark rounded-top-4">
                        <h5 class="mb-0">📨 الرسائل المستلمة من الأطباء</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($labMessages) && $labMessages->count() > 0)
                            <div class="list-group">
                                @foreach($labMessages as $msg)
                                    <div class="list-group-item border-warning">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1 text-warning">{{ $msg->test_type }}</h6>
                                            <span class="badge bg-{{ $msg->priority == 'طارئة' ? 'danger' : ($msg->priority == 'عاجلة' ? 'warning' : 'secondary') }}">
                                                {{ $msg->priority ?? 'عادية' }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>👨‍⚕️ الطبيب:</strong> {{ $msg->doctor->name ?? 'غير محدد' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>📋 التفاصيل:</strong> {{ $msg->test_details ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>💊 معلومات طبية:</strong> {{ $msg->medical_info ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>📝 رسالة:</strong> {{ $msg->message ?? '-' }}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                                            <button class="btn btn-sm btn-success" onclick="markAsCompleted({{ $msg->id }}, 'lab')">
                                                ✅ تم الإنجاز
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">لا توجد رسائل مستلمة من الأطباء.</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card shadow-sm mb-1 rounded-4">
                <div class="card-header bg-warning text-dark rounded-top-4">
                    🧪 التحاليل المخبرية
                    @if(auth()->user()->hasRole('الدكتور') && $visit->status != 5)
                        <button class="btn btn-sm btn-primary" onclick="confirmlabTests({{ $visit->id }})">
                            طلب تحليل
                        </button>
                    @endif

                    @if(auth()->user()->hasRole('فني المخبر'))
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLabTestModal">
                            ➕ إضافة تحليل مخبري
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($visit->labTests->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($visit->labTests as $lab)
                                <li class="list-group-item">
                                    <strong>النتيجة:</strong> {{ $lab->result ?? '-' }} <br>
                                    <strong>تقرير فني:</strong> {{ $lab->technical_report ?? '-' }} <br>
                                    <strong>الفني:</strong> {{ $lab->technician_name ?? '-' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mt-1">لا توجد تحاليل مخبرية لهذه الزيارة.</p>
                    @endif
                </div>
            </div>

            <!-- Modal لإضافة تحليل مخبري -->
            <div class="modal fade" id="addLabTestModal" tabindex="-1" aria-labelledby="addLabTestModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4 shadow-lg">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold" id="addLabTestModalLabel">➕ إضافة تحليل مخبري</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <form action="{{ route('lab_tests.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">

                                <div class="mb-3">
                                    <label for="result" class="form-label fw-bold">🔬 النتيجة</label>
                                    <textarea name="result" id="result" rows="3" class="form-control" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="technical_report" class="form-label fw-bold">📝 التقرير الفني</label>
                                    <textarea name="technical_report" id="technical_report" rows="3"
                                        class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="technician_name" class="form-label fw-bold">👨‍🔬 اسم الفني</label>
                                    <input type="text" name="technician_name" id="technician_name" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-success">💾 حفظ التحليل</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if(auth()->user()->hasRole('فني العمليات') || auth()->user()->hasRole('الدكتور'))
            <div class="card shadow-sm mb-1 rounded-4">
                <div class="card-header bg-danger text-dark rounded-top-4">
                    ⚕️ العمليات
                    @if(auth()->user()->hasRole('الدكتور') && $visit->status != 5)
                        <button class="btn btn-sm btn-primary" onclick="confirmSurgerys({{ $visit->id }})">
                            طلب عملية
                        </button>
                    @endif

                    @if(auth()->user()->hasRole('فني العمليات') && $visit->status != 5)
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSurgeryModal">
                            ⚕️ اضافة عملية
                        </button>
                    @endif
                </div>

                <div class="card-body mt-2">
                    @if($visit->surgeries->count() > 0)
                        @foreach($visit->surgeries as $surgery)
                            <div class="mb-3 border rounded p-3 shadow-sm">
                                <p><strong>نوع العملية:</strong> {{ $surgery->surgery_type }}</p>
                                <p><strong>تاريخ البداية:</strong> {{ $surgery->start_time }}</p>
                                <p><strong>تاريخ النهاية:</strong> {{ $surgery->end_time }}</p>
                                <p><strong>ملاحظات:</strong> {{ $surgery->notes ?? '-' }}</p>

                                <!-- تفاصيل العملية -->
                                @if($surgery->procedures->count() > 0)
                                    <h6 class="mt-3">تفاصيل العملية:</h6>
                                    <ul class="list-group mb-2">
                                        @foreach($surgery->procedures as $proc)
                                            <li class="list-group-item">
                                                <strong>نوع الإجراء:</strong> {{ $proc->procedure_type }} <br>
                                                <strong>الأدوات:</strong> {{ $proc->equipment ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">لا توجد تفاصيل مسجلة لهذه العملية.</p>
                                @endif

                                <!-- زر فتح مودال إضافة التفاصيل -->
                                @if(auth()->user()->hasRole('فني العمليات'))
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#addProcedureModal-{{ $surgery->id }}">
                                        ➕ إضافة تفاصيل العملية
                                    </button>
                                @endif
                            </div>

                            <!-- Modal: إضافة تفاصيل العملية -->
                            <div class="modal fade" id="addProcedureModal-{{ $surgery->id }}" tabindex="-1"
                                aria-labelledby="addProcedureModalLabel-{{ $surgery->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 shadow">
                                        <div class="modal-header bg-primary text-white rounded-top-4">
                                            <h5 class="modal-title" id="addProcedureModalLabel-{{ $surgery->id }}">
                                                ➕ إضافة تفاصيل العملية
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <form action="{{ route('surgery_procedures.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="surgery_id" value="{{ $surgery->id }}">

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">نوع الإجراء</label>
                                                    <input type="text" name="procedure_type" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">الأدوات المستخدمة</label>
                                                    <textarea name="equipment" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-success">💾 حفظ التفاصيل</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mt-1">لا توجد عمليات لهذه الزيارة.</p>
                    @endif
                </div>
            </div>

            <!-- Modal: إضافة عملية -->
            <div class="modal fade" id="addSurgeryModal" tabindex="-1" aria-labelledby="addSurgeryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header bg-danger text-white rounded-top-4">
                            <h5 class="modal-title" id="addSurgeryModalLabel">⚕️ إضافة عملية جديدة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>

                        <form action="{{ route('surgeries.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                            <input type="hidden" name="doctor_id" value="{{ $visit->appointment->doctor_id ?? auth()->id() }}">

                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">نوع العملية</label>
                                        <input type="text" name="surgery_type" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">وقت البداية</label>
                                        <input type="datetime-local" name="start_time" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">وقت النهاية</label>
                                        <input type="datetime-local" name="end_time" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-danger">💾 حفظ العملية</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->hasRole('ممرض الجناح') || auth()->user()->hasRole('الدكتور'))
            
            <div class="card shadow-sm mb-4 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <span>💊 الوصفات الطبية</span>
                    @if(auth()->user()->hasRole('الدكتور') && $visit->status != 5)
                         <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                            ➕ إضافة وصفة
                        </button>
                    @endif

                    @if(auth()->user()->hasRole('ممرض الجناح') && $visit->status != 5)
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                            ➕ إضافة وصفة
                        </button>
                    @endif
                </div>

                <div class="card-body">
                    @if($visit->prescriptions->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($visit->prescriptions as $prescription)
                                <li class="list-group-item d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <strong>{{ $prescription->medicine_name }}</strong>
                                        <small class="text-muted d-block">
                                            {{ $prescription->dosage }} - لمدة {{ $prescription->duration }}
                                        </small>
                                    </div>
                                    <!-- زر عرض التفاصيل -->
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#prescriptionDetailsModal{{ $prescription->id }}">
                                        عرض التفاصيل
                                    </button>
                                </li>

                                <!-- Modal: تفاصيل الوصفة -->
                                <div class="modal fade" id="prescriptionDetailsModal{{ $prescription->id }}" tabindex="-1"
                                    aria-labelledby="prescriptionDetailsLabel{{ $prescription->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="prescriptionDetailsLabel{{ $prescription->id }}">
                                                    تفاصيل الوصفة الطبية
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>💊 الدواء:</strong> {{ $prescription->medicine_name }}</p>
                                                <p><strong>📦 الجرعة:</strong> {{ $prescription->dosage }}</p>
                                                <p><strong>⏳ المدة:</strong> {{ $prescription->duration }}</p>
                                                <p><strong>📝 ملاحظات:</strong> {{ $prescription->notes ?? '-' }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mt-1">لا توجد وصفات طبية لهذه الزيارة.</p>
                    @endif
                </div>
            </div>

            <!-- Modal: إضافة وصفة جديدة -->
            @if(auth()->user()->hasRole('الدكتور'))
                <div class="modal fade" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="addPrescriptionLabel">➕ إضافة وصفة طبية</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                            </div>
                            <form action="{{ route('prescriptions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">

                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label class="form-label">اسم الدواء</label>
                                        <input type="text" name="medicine_name" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">الجرعة</label>
                                        <input type="text" name="dosage" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">المدة</label>
                                        <input type="text" name="duration" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn btn-success">💾 حفظ الوصفة</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- قسم عرض الرسائل المكتملة والمعلقة -->
        <!-- @if(auth()->user()->hasRole('الدكتور'))
            <div class="card shadow-sm mb-3 rounded-4">
                <div class="card-header bg-secondary text-white rounded-top-4">
                    <h5 class="mb-0">📊 حالة الرسائل المرسلة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-info">🖼️ رسائل الأشعة</h6>
                            @if(isset($xrayMessages) && $xrayMessages->count() > 0)
                                <div class="list-group">
                                    @foreach($xrayMessages->take(3) as $msg)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $msg->examination_type }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                            <span class="badge bg-{{ $msg->status == 'مكتمل' ? 'success' : ($msg->status == 'قيد التنفيذ' ? 'warning' : 'secondary') }}">
                                                {{ $msg->status ?? 'جديد' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                @if($xrayMessages->count() > 3)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">و {{ $xrayMessages->count() - 3 }} رسائل أخرى...</small>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">لا توجد رسائل مرسلة لفني الأشعة</p>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        @endif -->

        <!-- Modal إلغاء الموعد من صفحة الزيارة -->
        <div class="modal fade" id="cancelVisitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('visits.cancel', $visit->id) }}">
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

    </div>
    <script>
        function confirmXray(visitId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "هل تريد فعلاً طلب صورة أشعة لهذا المريض؟",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، اطلب الآن',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/visits/xray/" + visitId;
                }
            });
        }
        function confirmlabTests(visitId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "هل تريد فعلاً تحليل مخبري لهذا المريض؟",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، اطلب الآن',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/visits/labTests/" + visitId;
                }
            });
        }
        function confirmSurgerys(visitId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "هل تريد طلب عملية لهذا المريض؟",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، اطلب الآن',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/visits/surgeries/" + visitId;
                }
            });
        }
        function confirmPrescription(visitId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "هل تريد انشاء وصفة طبية لهذا المريض؟",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، اطلب الآن',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/visits/prescriptions/" + visitId;
                }
            });
        }

        // دالة تحديث حالة الرسالة إلى مكتملة
        function markAsCompleted(messageId, type) {
            Swal.fire({
                title: 'تأكيد الإنجاز',
                text: `هل تريد تأكيد إنجاز ${type === 'xray' ? 'فحص الأشعة' : 'التحليل المخبري'}؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، تم الإنجاز',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إرسال طلب AJAX لتحديث الحالة
                    fetch(`/messages/${type}/${messageId}/complete`, {
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
        .card-header {
            font-size: 1.1rem;
        }

        .card-body p,
        .card-body li {
            font-size: 0.95rem;
        }

        img.card-img-top {
            height: 200px;
            object-fit: cover;
        }

        /* أنماط إضافية للرسائل */
        .list-group-item {
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.8em;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .optgroup {
            font-weight: 600;
            color: #6c757d;
        }

        .optgroup option {
            font-weight: normal;
            color: #212529;
        }

        /* تحسين أزرار الإجراءات */
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        /* تأثيرات بصرية للرسائل */
        .border-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-warning {
            border-left: 4px solid #ffc107 !important;
        }

        /* تحسين عرض الرسائل */
        .message-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .priority-urgent {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
        }

        .priority-high {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-color: #ffc107;
        }
    </style>
@endsection