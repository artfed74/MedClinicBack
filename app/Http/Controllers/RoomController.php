<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Resources\StoreRoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(){
        $rooms = Room::all();
        return StoreRoomResource::collection($rooms);
    }
    public function store(StoreRoomRequest $request){
    $data = $request->validated();
    $room=Room::create($data);
    return (new StoreRoomResource($room))->response()->setStatusCode(201,"Кабинет создан!");
    }
}
