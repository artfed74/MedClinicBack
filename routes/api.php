<?php

use App\Http\Controllers\MedcardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Qualification;
use App\Http\Controllers\QualificationController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RewievController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Авторизация и выход
Route::post('/login',[UserController::class,'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);


Route::prefix('users')->group(function (){
    Route::post('/store',[UserController::class,'store']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/store-doctor', [UserController::class, 'store_doctor']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->get('/index', [UserController::class, 'index']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->get('/doctors', [UserController::class, 'index_doctor']);

});
Route::prefix('doctors')->group(function (){
    Route::get('/', [DoctorController::class, 'index']);
    Route::get('/{id}', [DoctorController::class, 'show']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->patch('/{id}', [DoctorController::class, 'update']);
    Route::middleware('auth:sanctum')->post('/{id}/upload-photo', [DoctorController::class, 'uploadPhoto']);

});
Route::prefix('patients')->group(function (){
    Route::middleware(['auth:sanctum', 'role:Пациент|Администратор|Врач'])->get('/{id}', [PatientController::class, 'show']);
    Route::middleware(['auth:sanctum', 'role:Пациент'])->patch('/{id}', [PatientController::class, 'update']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->get('/', [PatientController::class, 'index']);

});

//Функционал админа
Route::prefix('qualifications')->group(function (){
    Route::get('/',[QualificationController::class,'index']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/store',[QualificationController::class,'store']);
});
Route::prefix('specializations')->group(function (){
    Route::get('/',[SpecializationController::class,'index']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/store',[SpecializationController::class,'store']);
});



//Отзывы
Route::prefix('reviews')->group(function (){
    Route::get('/',[RewievController::class,'index']);
    Route::middleware(['auth:sanctum', 'role:Пациент'])->post('/store',[RewievController::class,'store']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->delete('/delete/{id}',[RewievController::class,'destroy']);

});

Route::post('/image',[TestController::class,'upload']);

//Работа с услугами

Route::prefix('services')->group(function (){
    Route::get('/',[ServiceController::class,'index']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/store', [ServiceController::class, 'store']);
    Route::middleware(['auth:sanctum'])->get('/{id}', [ServiceController::class, 'show']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->patch('/update/{id}', [ServiceController::class, 'update']);
    Route::middleware(['auth:sanctum', 'role:Администратор'])->delete('/delete/{id}', [ServiceController::class, 'destroy']);

});
Route::prefix('medcards')->group(function (){
    Route::middleware(['auth:sanctum', 'role:Пациент'])->post('/store',[MedcardController::class,'store']);
    Route::middleware(['auth:sanctum', 'role:Пациент'])->patch('/update/{id}',[MedcardController::class,'update']);

});
Route::prefix('rooms')->group(function (){
    Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/store',[RoomController::class,'store']);
    Route::get('/',[RoomController::class,'index']);
});
Route::middleware(['auth:sanctum', 'role:Администратор'])->post('/doctors/{id}/schedule', [DoctorScheduleController::class, 'store']);
Route::middleware(['auth:sanctum', 'role:Администратор'])->patch('schedule/{id}', [DoctorScheduleController::class, 'update']);
Route::middleware(['auth:sanctum', 'role:Администратор'])->get('schedule/{id}', [DoctorScheduleController::class, 'show']);

Route::middleware(['auth:sanctum', 'role:Пациент'])->post('/appointments', [AppointmentController::class, 'create']);
Route::middleware(['auth:sanctum', 'role:Пациент|Врач|Администратор'])->get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::middleware(['auth:sanctum', 'role:Пациент|Администратор'])->get('/appointments/patient/{patient_id}', [AppointmentController::class, 'showByPatientId']);
Route::middleware(['auth:sanctum', 'role:Врач|Администратор'])->get('/appointments/doctor/{doctor_id}', [AppointmentController::class, 'showByDoctorId']);
Route::middleware(['auth:sanctum', 'role:Врач|Администратор'])->patch('/appointments/start/{id}', [AppointmentController::class, 'markAsStart']);
Route::middleware(['auth:sanctum', 'role:Врач|Администратор'])->patch('/appointments/complete/{id}', [AppointmentController::class, 'markAsCompleted']);
Route::middleware(['auth:sanctum', 'role:Врач|Администратор'])->patch('/appointments/reject/{id}', [AppointmentController::class, 'markAsRejected']);
Route::middleware(['auth:sanctum', 'role:Врач'])->post('appointments/{id}/prescriptions', [PrescriptionController::class, 'store']);

Route::middleware(['auth:sanctum', 'role:Пациент'])->get('/patients/{patientId}/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{id}', [NotificationController::class, 'update']);
Route::get('/available-times', [AppointmentController::class, 'getAvailableTimes']);
Route::get('/available-dates', [AppointmentController::class, 'getAvailableDates']);
Route::get('/statistics', [StatisticController::class, 'index']);
Route::get('/statistics/general', [StatisticController::class, 'getGeneralStats']);
Route::get('/statistics/general?period=month', [StatisticController::class, 'getGeneralStats']);
