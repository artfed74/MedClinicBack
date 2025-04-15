<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualificationRequest;
use App\Http\Resources\IndexQualResource;
use App\Http\Resources\StoreQualificationResource;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    public function index(){
        $qualifications = Qualification::all();
        return IndexQualResource::collection($qualifications);
    }
    public function store(StoreQualificationRequest $request)
    {
        $data=$request->validated();
        $qualification=Qualification::create($data);
        return (new StoreQualificationResource($qualification))->response()->setStatusCode(201,"Квалификация добавлена");
    }
}
