<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientReductRequest;
use App\Http\Resources\IndexPatientResource;
use App\Http\Resources\PatientUpdateResource;
use App\Http\Resources\ShowPatientResource;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Patient::all();
        return IndexPatientResource::collection($data)->response()->setStatusCode(200,'Все пациенты');
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
        $patient=Patient::find($id);
        return (new ShowPatientResource($patient))->response()->setStatusCode(200,'Отдельный пациент');
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
    public function update(PatientReductRequest $request, $id)
    {
        // Логируем начало метода
        Log::info('Запущен метод update для пациента с ID:', ['id' => $id]);

        // Находим пациента по ID или выбрасываем ошибку
        $patient = Patient::findOrFail($id);
        Log::info('Пациент найден:', ['patient' => $patient]);

        $data = $request->validated();

        // Логируем полученные данные запроса
        Log::info('Данные запроса:', ['data' => $data]);

        // Обновляем данные пациента
        $data['policy_type'] = $data['policy_type'] === 'omc' ? 'ОМС' : 'ДМС';
        $patient->update([
            'passport_serial' => $data['passport_serial'],
            'passport_number' => $data['passport_number'],
            'policy_number' => $data['policy_number'],
            'policy_type' => $data['policy_type'],
        ]);

        // Логируем обновление пациента
        Log::info('Обновлены данные пациента:', ['patient' => $patient]);

        // Берём user_id из записи пациента
        $userId = $patient->user_id;
        Log::info('user_id пациента:', ['user_id' => $userId]);

        // Проверяем, существует ли пользователь
        $user = User::find($userId);
        if ($user) {
            // Логируем данные найденного пользователя
            Log::info('Пользователь найден:', ['user' => $user]);

            // Обновляем данные пользователя
            $user->update([
                'firstname' => $data['firstname'] ?? $user->firstname,
                'lastname' => $data['lastname'] ?? $user->lastname,
                'patronymic' => $data['patronymic'] ?? $user->patronymic,
                'email' => $data['email'] ?? $user->email,
                'date_birth' => $data['date_birth'] ?? $user->date_birth,
                'gender' => $data['gender'] ?? $user->gender,
            ]);

            // Логируем обновление пользователя
            Log::info('Обновлены данные пользователя:', ['user' => $user]);
        } else {
            // Логируем, если пользователь не найден
            Log::error('Пользователь с указанным user_id не найден:', ['user_id' => $userId]);

            return response()->json([
                'message' => 'Пользователь с указанным user_id не найден.',
            ], 404);
        }

        // Возвращаем обновлённые данные пациента
        Log::info('Метод update завершён успешно для пациента с ID:', ['id' => $id]);

        return (new PatientUpdateResource($patient))->response()->setStatusCode(200, 'Информация успешно обновлена!');
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
