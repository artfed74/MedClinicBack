<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\AllPatientsResource;
use App\Http\Resources\DoctorAddResource;
use App\Http\Resources\LoginResource;
use App\Http\Resources\PatientStoreResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('role', 'Пациент')->get();
      return AllPatientsResource::collection($data)->response()->setStatusCode(200,'Все пациенты клиники');
    }
    public function index_doctor()
    {
        $data = User::where('role', 'Врач')->get();
        return AllPatientsResource::collection($data)->response()->setStatusCode(200,'Все пациенты клиники');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        // Валидируем и получаем данные из запроса
        $data = $request->validated();

        // Преобразуем пол
        $data['gender'] = $data['gender'] === 'male' ? 'Мужской' : 'Женский';

        // Присваиваем роль пользователю
        $data['role'] = 'Пациент';

        // Хэшируем пароль
        $data['password'] = Hash::make($data['password']);

        // Создаем пользователя
        $user = User::create($data);

        // Создаем пациента и связываем его с пользователем
        $patient = Patient::create([
            'user_id' => $user->id,
        ]);

        // Возвращаем ресурс с данными пациента и пользователя
        return (new PatientStoreResource($patient))->response()->setStatusCode(201, 'Пациент успешно создан');
    }
    public function store_doctor(UserStoreRequest $request)
    {
        $data = $request->validated();

        // Преобразование гендера
        $data['gender'] = $data['gender'] === 'male' ? 'Мужской' : 'Женский';

        // Роль по умолчанию
        $data['role'] = 'Врач';

        // Хэшируем пароль
        $data['password'] = Hash::make($data['password']);

        // Создаем пользователя
        $user = User::create($data);

        // Логика для обработки изображения
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Генерация уникального имени файла
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

            // Сохранение файла в папке storage
            $destinationPath = public_path('storage');
            $file->move($destinationPath, $filename);

            // Путь к файлу
            $photoPath = 'storage/' . $filename;
        }

        // Создаем запись врача
        Doctor::create([
            'user_id' => $user->id,
            'photo' => $photoPath, // Сохраняем путь к фото (или null, если файла нет)
        ]);

        // Возвращаем ресурс с информацией о созданном враче
        return (new DoctorAddResource($user))
            ->response()
            ->setStatusCode(201, 'Врач успешно создан');
    }
    public function login(AuthRequest $request)
    {
        $data = $request->validated();

        // Попытка авторизовать пользователя
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();

            // Проверяем роль пользователя
            $patientId = null;
            $doctorId = null;
            $adminId = null;


            if ($user->role === 'Пациент') {
                // Ищем пациента по user_id
                $patient = Patient::where('user_id', $user->id)->first();
                $patientId = $patient ? $patient->id : null;
            } elseif ($user->role === 'Врач') {
                // Ищем врача по user_id
                $doctor = Doctor::where('user_id', $user->id)->first();
                $doctorId = $doctor ? $doctor->id : null;
            }
            elseif ($user->role === 'Администратор') {
                // Ищем врача по user_id
                $admin = User::where('id', $user->id)->first();
                $adminId = $admin ? $admin->id : null;
            }

            // Удаляем все старые токены
            $user->tokens()->delete();

            // Создаём новый токен для пользователя
            $token = $user->createToken('api')->plainTextToken;

            // Возвращаем данные с токеном, id пользователя, ролью, patient_id и doctor_id
            return (new LoginResource([
                'token' => $token,
                'id' => $user->id,
                'role' => $user->role,
                'patient_id' => $patientId,  // ID пациента
                'doctor_id' => $doctorId,    // ID врача
                'admin_id'=>$adminId
            ]))
                ->response()
                ->setStatusCode(200, 'Успешная авторизация');
        }

        return response()->json([
            'message' => 'Неверный email или пароль'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Вы успешно вышли из системы!'
        ])->setStatusCode(200,'Вы успешно вышли из системы!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
