<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\searchHistory;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\recipe;

class searchHistoryController extends Controller
{
    public function index(){
        $id = $request->user()->id;
        $searchHistory = searchHistory::where('user_id', $id)->latest()->take(20)->with('user', 'recipe',)->get();

        if(count($searchHistory) > 0){
            return response([
                'message' => 'Retrieve Search History Success',
                'data' => $searchHistory
            ], 200);
        }
    }

    public function store(Request $request){
        $storeData = $request->all();
        $storeData['user_id'] = $request->user()->id;
        $validate = Validator::make($storeData, [
            'recipe_id' => 'required',
            'user_id' => 'required',
            'searched_at' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()->first(),'errors' => $validate->errors()], 400);

        $searchHistory = searchHistory::create($storeData);
        return response([
            'message' => 'Add Search History Success',
            'data' => $searchHistory
        ], 200);
    }

}