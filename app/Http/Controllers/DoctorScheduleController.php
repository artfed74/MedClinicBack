<?php

namespace App\Http\Controllers;

use App\Http\Requests\SheduleStoreRequest;
use App\Http\Requests\SheduleUpdateRequest;
use App\Http\Resources\SheduleStoreResource;
use App\Models\Doctor_Shedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function store(SheduleStoreRequest $request){
        $data = $request->validated();
        $shedule = Doctor_Shedule::create($data);
        return (new SheduleStoreResource($shedule))->response()->setStatusCode(201,"Расписание добавлено");
    }
    public function update(SheduleUpdateRequest $request, $id)
    {
        $schedule = Doctor_Shedule::findOrFail($id);

        $data = $request->validated();

        $schedule->update($data);

        return (new SheduleStoreResource($schedule))->response()->setStatusCode(200, "Расписание обновлено");
    }
    public function show($id)
    {
        $schedule = Doctor_Shedule::findOrFail($id);
        return (new SheduleStoreResource($schedule))->response()->setStatusCode(200, "Инфа о расписании");

    }
}
