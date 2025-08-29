<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorVisitsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\XRayImageController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\SurgeryProcedureController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    return redirect('/dashboard');
});

Route::get('/dashboard', [Controller::class, 'dashboard'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/users', function () {
        return view('users.index');
    })->middleware('auth')->name('users.index');
    Route::get('/users/edit/{id}', 'UserController@edit')->name('users.edit');


    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('user-create', [UserController::class, 'create_user']);
    Route::resource('products', ProductController::class);
});

Route::middleware(['auth'])->group(function () {
    // طلبات الرعاية التمريضية
    Route::get('nursing-requests', [\App\Http\Controllers\NursingCareRequestController::class, 'index'])->name('nursing_requests.index');
    Route::post('nursing-requests/store', [\App\Http\Controllers\NursingCareRequestController::class, 'store'])->name('nursing_requests.store');
    Route::post('nursing-requests/{id}/accept', [\App\Http\Controllers\NursingCareRequestController::class, 'accept'])->name('nursing_requests.accept');
    Route::post('nursing-requests/{id}/complete', [\App\Http\Controllers\NursingCareRequestController::class, 'complete'])->name('nursing_requests.complete');
});
Route::middleware(['auth'])
    ->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions-create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('departments/store', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
        Route::get('departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('departments/{id}/update', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('departments/{id}/delete', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{id}/update', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('appointments/{id}/delete', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
        Route::get('reservations/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('reservations/{id}/update', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('reservations/{id}/delete', [ReservationController::class, 'destroy'])->name('reservations.destroy');

        Route::get('xrays', [XRayImageController::class, 'index'])->name('xrays.index');
        Route::get('xrays/create', [XRayImageController::class, 'create'])->name('xray_images.create');
        Route::post('xrays/store', [XRayImageController::class, 'store'])->name('xrays.store');
        Route::get('xrays/{id}', [XRayImageController::class, 'show'])->name('xrays.show');
        Route::get('xrays/{id}/edit', [XRayImageController::class, 'edit'])->name('xrays.edit');
        Route::put('xrays/{id}/update', [XRayImageController::class, 'update'])->name('xrays.update');
        Route::delete('xrays/{id}/delete', [XRayImageController::class, 'destroy'])->name('xrays.destroy');

        Route::get('labtests', [LabTestController::class, 'index'])->name('lab_tests.index');
        Route::get('labtests/create', [LabTestController::class, 'create'])->name('lab_tests.create');
        Route::post('labtests/store', [LabTestController::class, 'store'])->name('lab_tests.store');
        Route::get('labtests/{id}', [LabTestController::class, 'show'])->name('lab_tests.show');
        Route::get('labtests/{id}/edit', [LabTestController::class, 'edit'])->name('lab_tests.edit');
        Route::put('labtests/{id}/update', [LabTestController::class, 'update'])->name('lab_tests.update');
        Route::delete('labtests/{id}/delete', [LabTestController::class, 'destroy'])->name('lab_tests.destroy');

        Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('prescriptions/store', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('prescriptions/{id}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
        Route::get('prescriptions/{id}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('prescriptions/{id}/update', [PrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('prescriptions/{id}/delete', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');

        Route::get('surgeries', [SurgeryController::class, 'index'])->name('surgeries.index');
        Route::get('surgeries/create', [SurgeryController::class, 'create'])->name('surgeries.create');
        Route::post('surgeries/store', [SurgeryController::class, 'store'])->name('surgeries.store');
        Route::get('surgeries/{id}', [SurgeryController::class, 'show'])->name('surgeries.show');
        Route::get('surgeries/{id}/edit', [SurgeryController::class, 'edit'])->name('surgeries.edit');
        Route::put('surgeries/{id}/update', [SurgeryController::class, 'update'])->name('surgeries.update');
        Route::delete('surgeries/{id}/delete', [SurgeryController::class, 'destroy'])->name('surgeries.destroy');

        Route::get('surgeryprocedures', [SurgeryProcedureController::class, 'index'])->name('surgery_procedures.index');
        Route::get('surgeryprocedures/create', [SurgeryProcedureController::class, 'create'])->name('surgery_procedures.create');
        Route::post('surgeryprocedures/store', [SurgeryProcedureController::class, 'store'])->name('surgery_procedures.store');
        Route::get('surgeryprocedures/{id}', [SurgeryProcedureController::class, 'show'])->name('surgery_procedures.show');
        Route::get('surgeryprocedures/{id}/edit', [SurgeryProcedureController::class, 'edit'])->name('surgery_procedures.edit');
        Route::put('surgeryprocedures/{id}/update', [SurgeryProcedureController::class, 'update'])->name('surgery_procedures.update');
        Route::delete('surgeryprocedures/{id}/delete', [SurgeryProcedureController::class, 'destroy'])->name('surgery_procedures.destroy');

        Route::get('reception', [ReceptionController::class, 'index'])->name('reception.index');
        Route::get('reception/create', [ReceptionController::class, 'create'])->name('reception.create');
        Route::post('reception/store', [ReceptionController::class, 'store'])->name('reception.store');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
        Route::get('/visits/show/{visit}', [VisitController::class, 'show'])->name('visits.show');
        Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
        Route::post('/visits/store', [VisitController::class, 'store'])->name('visits.store');
        Route::get('/visits/{id}/edit', [VisitController::class, 'edit'])->name('visits.edit');
        Route::post('/visits/{id}/update', [VisitController::class, 'update'])->name('visits.update');
        Route::delete('/visits/{id}/delete', [VisitController::class, 'destroy'])->name('visits.destroy');
        Route::get('my-visits', [VisitController::class, 'myVisits'])->name('visits.my');
        Route::post('/visits/store-for-user', [VisitController::class, 'storeForUser'])->name('visits.storeForUser');

        Route::get('/visits/xray/{id}', [VisitController::class, 'xray'])->name('visits.xray');
        Route::get('/visits/labTests/{id}', [VisitController::class, 'labTests'])->name('visits.labTests');
        Route::get('/visits/surgeries/{id}', [VisitController::class, 'surgeries'])->name('visits.surgeries');
        Route::get('/visits/prescriptions/{id}', [VisitController::class, 'prescriptions'])->name('visits.prescriptions');
        Route::get('/doctor/appointments', [DoctorVisitsController::class, 'doctorAppointments'])
            ->name('doctor.appointments');


        Route::get('/visits/{visit}/export-pdf', [VisitController::class, 'exportVisitPdf'])->name('visits.exportPDF');

    });

require __DIR__ . '/auth.php';
