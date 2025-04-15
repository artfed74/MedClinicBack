<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedcardRequest;
use App\Http\Requests\UpdateMedcardRequest;
use App\Http\Resources\StoreMedcardResource;
use App\Models\Medcard;
use Illuminate\Http\Request;

class MedcardController extends Controller
{
    public function store(StoreMedcardRequest $request){

        $data = $request->validated();
        if($data['blood_type']=='first'){
            $data['blood_type']='Первая';
        }
        if($data['blood_type']=='second'){
            $data['blood_type']='Вторая';
        }
        if($data['blood_type']=='third'){
            $data['blood_type']='Третья';
        }
        if($data['blood_type']=='fourth'){
            $data['blood_type']='Четвёртая';
        }
        $medcard = Medcard::create($data);
        return (new StoreMedcardResource($medcard))->response()->setStatusCode(201,'Мадкарта доабвлена');
    }
    public function update(UpdateMedcardRequest $request,$id){
        $medcard=Medcard::find($id);
        $data = $request->validated();
        if($data['blood_type']=='first'){
            $data['blood_type']='Первая';
        }
        if($data['blood_type']=='second'){
            $data['blood_type']='Вторая';
        }
        if($data['blood_type']=='third'){
            $data['blood_type']='Третья';
        }
        if($data['blood_type']=='fourth'){
            $data['blood_type']='Четвёртая';
        }
        $medcard->update($data);
        return (new StoreMedcardResource($medcard))->response()->setStatusCode(200,'Информация обновалена');
    }
}
