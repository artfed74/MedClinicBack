<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewIndexResource;
use App\Http\Resources\StoreReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class RewievController extends Controller
{
    public function index(){
    $data=Review::all();
    return ReviewIndexResource::collection($data)->response()->setStatusCode(200,'Все отзывы');
    }
    public function store(StoreReviewRequest $request){
    $data = $request->validated();
    $review = Review::create($data);
    return (new StoreReviewResource($review))->response()->setStatusCode(201,'Отзыв создан');
    }
    public function destroy($id){
        $review = Review::find($id);
        $review->delete();
        return 'Успешное удаление отзыва';
    }
}
