<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorReductRequest;
use App\Http\Resources\DoctorReductResource;
use App\Http\Resources\IndexDoctorResource;
use App\Http\Resources\ShowDoctorResource;
use App\Models\Doctor;
use App\Models\Qualification;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::all();
        return IndexDoctorResource::collection($doctors)->response()->setStatusCode(200,'Все врачи клиники');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Находим врача с услугами и расписанием
        $doctor = Doctor::with(['services', 'schedule'])->find($id);

        // Если врач не найден
        if (!$doctor) {
            return response()->json([
                'message' => 'Врач не найден',
            ], 404);
        }

        // Возвращаем данные через ресурс
        return (new ShowDoctorResource($doctor))
            ->response()
            ->setStatusCode(200, 'Информация о враче');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorReductRequest $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $data = $request->validated();

        // Фетч квалификации и специализации
        $qualification = Qualification::find($data['qualification_id']);
        $specialization = Specialization::find($data['specialization_id']);

        if (!$qualification) {
            $qualification = Qualification::first();
        }
        if (!$specialization) {
            $specialization = Specialization::first();
        }

        // Обновление данных врача
        $doctor->update([
            'experience' => $data['experience'],
            'specialization_id' => $specialization->id,
            'qualification_id' => $qualification->id,
        ]);

        $userId = $doctor->user_id;
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'firstname' => $data['firstname'] ?? $user->firstname,
                'lastname' => $data['lastname'] ?? $user->lastname,
                'patronymic' => $data['patronymic'] ?? $user->patronymic,
                'email' => $data['email'] ?? $user->email,
                'date_birth' => $data['date_birth'] ?? $user->date_birth,
                'gender' => $data['gender'] ?? $user->gender,
            ]);
        } else {
            return response()->json(['message' => 'Пользователь с указанным user_id не найден.'], 404);
        }

        // Обработка и сохранение фото
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $filename); // Сохраняем файл в папку storage/app/public/photos
            $data['photo'] = 'storage/photos/' . $filename;

            // Обновляем фото у врача
            $doctor->update(['photo' => $data['photo']]);
        }

        return (new DoctorReductResource($doctor))->response()->setStatusCode(200, 'Информация о враче успешно обновлена');
    }
    public function uploadPhoto(Request $request, $id)
    {
        // Валидируем входные данные
        $validator = Validator::make($request->all(), [
            'photo' => 'required', // Проверяем фото
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Находим врача по ID
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['error' => 'Доктор не найден'], 404);
        }

        // Если есть фото в запросе, обрабатываем его
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Генерация уникального имени файла
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

            // Сохранение файла в папке storage
            $destinationPath = public_path('storage');
            $file->move($destinationPath, $filename);
            $doctor->photo = 'storage/' . $filename;

        }
            // Обновляем данные врача с новым фото
            $doctor->save();


        return response()->json(['message' => 'Фото успешно обновлено', 'doctor' => $doctor], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
