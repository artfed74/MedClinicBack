<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionRequest;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function store($id, StorePrescriptionRequest $request)
    {
        // Находим запись о приеме по ID
        $appointment = Appointment::findOrFail($id);

        // Проверяем статус записи
        if ($appointment->status !== 'В процессе') {
            return response()->json(['error' => 'Запись не может быть завершена, так как она не начата'], 422);
        }

        // Создаем новый лист назначения
        $prescription = Prescription::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id, // берем doctor_id из записи appointment
            'medication' => $request->medication,
            'comment' => $request->comment,
            'prescription_date' => now(), // сегодняшняя дата
        ]);

        // Обновляем статус записи на "Завершён"
        $appointment->update(['status' => 'Завершён']);
        
        $notification = new Notification();
        $notification->appointment_id = $appointment->id;
        $notification->patient_id = $appointment->patient_id;
        $notification->message = "Приём номер {$appointment->id} был завершён. Вы можете перейти и ознакомиться с информацией";
        $notification->save();

        return response()->json($prescription, 201);
    }
}
