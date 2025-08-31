@extends('layouts.app')

@section('content')
<div class="container">
    <h3>طلبات الرعاية التمريضية</h3>
    <table class="table table-bordered" id="requests-table">
        <thead>
            <tr>
                <th>المريض</th>
                <th>الدكتور</th>
                <th>الرسالة</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                <tr onclick="openDialog({{ $request->id }})" style="cursor:pointer;">
                    <td>{{ $request->patient->first_name ?? '' }} {{ $request->patient->last_name ?? '' }}</td>
                    <td>{{ $request->doctor->first_name ?? '' }} {{ $request->doctor->last_name ?? '' }}</td>
                    <td>{{ $request->message }}</td>
                    <td>{{ $request->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Dialog -->
<div class="modal fade" id="nursingDialog" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">تفاصيل الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="request-details" class="mb-3"></div>

        <h5>الإجراءات السابقة</h5>
        <div id="actions-list" class="mb-3"></div>

        <h5>إضافة إجراء جديد</h5>
        <form id="action-form">
            @csrf
            <input type="hidden" name="nursing_care_request_id" id="request_id">
            <div class="mb-2">
                <textarea name="action" id="action_text" class="form-control" placeholder="اكتب الإجراء..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success" id="saveBtn">حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function openDialog(requestId) {
    $("#request_id").val(requestId);
    $("#nursingDialog").modal('show');

    // جلب تفاصيل الطلب
    $.get("{{ url('nursing_requests') }}/" + requestId, function(data) {
        let details = `
            <p><strong>المريض:</strong> ${data.patient.first_name} ${data.patient.last_name}</p>
            <p><strong>الدكتور:</strong> ${data.doctor.first_name} ${data.doctor.last_name}</p>
            <p><strong>الرسالة:</strong> ${data.message}</p>
            <p><strong>الحالة:</strong> ${data.status}</p>
        `;
        $("#request-details").html(details);

        // الإجراءات
        let html = "<ul class='list-group'>";
        data.actions.forEach(item => {
            let date = new Date(item.created_at);
            let formattedDate = date.toLocaleDateString('ar-EG') + " " + date.toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});
            html += `<li class='list-group-item d-flex justify-content-between align-items-center'>
                        <div>
                            <strong>وقت اضافه الاجراء : </strong> ${formattedDate}<br>
                            ${item.action}
                        </div>
                        <div>
                            <button class="btn btn-sm btn-warning" onclick="editAction(${item.id}, '${item.action}')">تعديل</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteAction(${item.id})">حذف</button>
                        </div>
                     </li>`;
        });
        html += "</ul>";
        $("#actions-list").html(html);

        // إعادة تفعيل زر الحفظ
        $("#saveBtn").prop("disabled", false).text("حفظ");
    });
}

// إضافة إجراء جديد
$("#action-form").submit(function(e){
    e.preventDefault();
    let saveBtn = $("#saveBtn");
    saveBtn.prop("disabled", true).text("جاري الحفظ...");

    $.ajax({
        url: "{{ route('nursing_requests.actions.store') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(){
            openDialog($("#request_id").val());
            $("#action_text").val("");
        },
        error: function(){
            alert("حدث خطأ، حاول مرة أخرى.");
            saveBtn.prop("disabled", false).text("حفظ");
        }
    });
});

// تعديل إجراء
function editAction(id, text) {
    let newText = prompt("تعديل الإجراء:", text);
    if(newText) {
        $.ajax({
            url: "{{ url('nursing_requests/actions') }}/" + id,
            type: "PUT",
            data: { _token: "{{ csrf_token() }}", action: newText },
            success: function(){
                openDialog($("#request_id").val());
            }
        });
    }
}

// حذف إجراء
function deleteAction(id) {
    if(confirm("هل أنت متأكد من حذف هذا الإجراء؟")) {
        $.ajax({
            url: "{{ url('nursing_requests/actions') }}/" + id,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function(){
                openDialog($("#request_id").val());
            }
        });
    }
}
</script>
@endpush
