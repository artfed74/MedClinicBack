<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetAppointmentsPatientResource;
use App\Models\Appointment;
use App\Models\Doctor_Shedule;
use App\Models\Notification;
use App\Models\Room;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function create(Request $request)
    {
        // Валидация входящих данных
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|integer',
            'service_id' => 'required|integer',
            'appointment_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Получаем doctor_id по service_id
        $service = Service::find($request->service_id);

        if (!$service) {
            return response()->json(['error' => 'Услуга не найдена'], 404);
        }

        $doctor_id = $service->doctor_id;
        $schedule = Doctor_Shedule::where('doctor_id', $doctor_id)->first();

        if (!$schedule) {
            return response()->json(['error' => 'Расписание не найдено'], 404);
        }

        $room = Room::where('doctor_id', $doctor_id)->first();

        if (!$room) {
            return response()->json(['error' => 'Кабинет не найден для данного врача'], 404);
        }

        // Проверка наличия уже существующей записи с таким же временем
        $existingAppointment = Appointment::where('appointment_time', $request->appointment_time)
            ->where('doctor_id', $doctor_id)
            ->first();

        if ($existingAppointment) {
            return response()->json(['error' => 'На это время уже существует запись'], 422);
        }

        $appointmentTime = Carbon::parse($request->appointment_time);
        $appointmentDay = $appointmentTime->format('l');

        \Log::info("Запрашиваемая дата: " . $appointmentTime);
        \Log::info("День недели: " . $appointmentDay);

        $workingHours = collect($schedule->schedule)->firstWhere('day_of_week', $appointmentDay);

        if (!$workingHours) {
            return response()->json(['error' => 'Врач не работает в этот день'], 422);
        }

        $start_time = $appointmentTime->copy()->setTimeFromTimeString($workingHours['start_time']);
        $end_time = $appointmentTime->copy()->setTimeFromTimeString($workingHours['end_time']);

        \Log::info("Рабочие часы: " . json_encode($workingHours));
        \Log::info("Время начала: " . $start_time->toTimeString());
        \Log::info("Время завершения: " . $end_time->toTimeString());
        \Log::info("Запрашиваемое время: " . $appointmentTime->toTimeString());

        if ($appointmentTime->lessThan($start_time) || $appointmentTime->greaterThan($end_time)) {
            return response()->json(['error' => 'Врач не доступен в это время'], 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor_id,
            'shedule_id' => $schedule->id,
            'service_id' => $request->service_id,
            'room_id' => $room->id,
            'appointment_time' => $request->appointment_time,
            'status' => 'В ожидании',
        ]);

        return response()->json($appointment, 201);
    }
    public function getAvailableTimes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Неверные входные данные'], 422);
        }

        $service = Service::find($request->service_id);

        if (!$service) {
            return response()->json(['error' => 'Услуга не найдена'], 404);
        }

        $doctor_id = $service->doctor_id;
        $schedule = Doctor_Shedule::where('doctor_id', $doctor_id)->first();

        if (!$schedule) {
            return response()->json(['error' => 'Расписание не найдено'], 404);
        }

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->format('l');

        $workingHours = collect($schedule->schedule)->firstWhere('day_of_week', $dayOfWeek);

        if (!$workingHours) {
            return response()->json(['available_times' => []]); // врач не работает в этот день
        }

        $start = Carbon::parse("{$request->date} {$workingHours['start_time']}");
        $end = Carbon::parse("{$request->date} {$workingHours['end_time']}");

        $allSlots = [];
        while ($start < $end) {
            $allSlots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        // Получаем уже занятые слоты
        $busySlots = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('appointment_time', $date)
            ->pluck('appointment_time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // Оставляем только свободные
        $available = array_values(array_diff($allSlots, $busySlots));

        return response()->json(['available_times' => $available]);
    }
    public function showByPatientId($patient_id)
    {
        if (!is_numeric($patient_id) || $patient_id <= 0) {
            return response()->json(['error' => 'Некорректный идентификатор пациента'], 422);
        }

        $appointments = Appointment::where('patient_id', $patient_id)->with(['doctor.user', 'service'])->get();

        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'Нет записей для данного пациента'], 404);
        }

        return GetAppointmentsPatientResource::collection($appointments);
    }
    public function showByDoctorId($doctor_id)
    {
        if (!is_numeric($doctor_id) || $doctor_id <= 0) {
            return response()->json(['error' => 'Некорректный идентификатор пациента'], 422);
        }

        $appointments = Appointment::where('doctor_id', $doctor_id)->with(['doctor.user', 'service'])->get();

        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'Нет записей для данного пациента'], 404);
        }

        return GetAppointmentsPatientResource::collection($appointments);
    }
    public function markAsStart($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }

        $appointment->status = 'В процессе';
        $appointment->save();

        return response()->json($appointment, 200);
    }
    public function markAsCompleted($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }

        $appointment->status = 'Завершён';
        $appointment->save();

        // Создать уведомление
        $notification = new Notification();
        $notification->appointment_id = $appointment->id;
        $notification->patient_id = $appointment->patient_id;
        $notification->message = "Приём номер {$appointment->id} был завершён. Вы можете перейти и ознакомиться с информацией";
        $notification->save();

        return response()->json($appointment, 200);
    }
    public function markAsRejected($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }

        $appointment->status = 'Отменён';
        $appointment->save();

        return response()->json($appointment, 200);
    }
    public function show($id)
    {
        $appointment = Appointment::find($id);
        return (new GetAppointmentsPatientResource($appointment))->response()->setStatusCode(200,'Инфо о записи');
    }
}
