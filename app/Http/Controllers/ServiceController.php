<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\StoreReviewResource;
use App\Http\Resources\StoreServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $data=Service::all();
        return StoreServiceResource::collection($data);
    }
    public function show($id){
        $service = Service::find($id);
        return new StoreServiceResource($service);
    }
    public function store(StoreServiceRequest $request){
        $data=$request->validated();
        $service=new Service($data);
        $service->save();
        $service->load('doctor');
        return (new StoreServiceResource($service))->response()->setStatusCode(201,"Услуга добавлена");
    }
    public function destroy($id)
    {
        $service=Service::find($id);
        $service->delete();
        return "Услуга удалена";
    }
    public function update(UpdateServiceRequest $request, $id){
        $service = Service::find($id);
        $data = $request->validated();
        $service->update($data);
        return $service;
    }
}
