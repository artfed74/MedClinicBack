<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Resources\IndexSpecResource;
use App\Http\Resources\StoreSpecializationResource;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index(){
       $specializations = Specialization::all();
       return IndexSpecResource::collection($specializations);
    }
    public function store(StoreSpecializationRequest $request)
    {
        $data=$request->validated();
        $spec=Specialization::create($data);
        return (new StoreSpecializationResource($spec))->response()->setStatusCode(201,"Специализация создана");
    }
}
