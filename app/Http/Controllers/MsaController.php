<?php

namespace App\Http\Controllers;

use App\Http\Resources\MsaResource;
use App\Models\Msa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class MsaController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: [])
        ];
    }

    public function index(){
        $user =  auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $msas = Msa::where('user_id', $user->id)->get();;
        return MsaResource::collection($msas);
    }

    public function store(Request $request){
        $allowedFields = ['name', 'type', 'rating', 'genre', 'description'];

        $extraFields = array_diff(array_keys($request->all()), $allowedFields);

        if (count($extraFields) > 0) {
            return response()->json([
                'message' => 'Invalid fields provided',
                'error' => 'Unrecognized fields: ' . implode(', ', $extraFields)
            ], 400);
        }

        $valid = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:255',
            'type' => 'required|string|in:movie,anime,series,other',
            'rating' => 'nullable|numeric|min:0|max:10',
            'description' => 'required|string|min:5|max:1000',
            'genre' => 'nullable|array',
            'genre.*' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => 'Invalid request',
                'error' => $valid->messages()
            ], 400);
        }


        $item = $request->user()->msas()->create($request->only(['name', 'type', 'rating', 'genre', 'description']));

        return response()->json([
            'message' => 'Data added successfully',
            'data' => new MsaResource($item)
        ], 201);
    }

    public function show(Msa $msa){
        $user = auth('sanctum')->user();

        if($msa->user_id !== $user->id){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new MsaResource($msa);
    }

    public function update(Msa $msa, Request $request){
        $user = auth('sanctum')->user();

        if($msa->user_id !== $user->id){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $allowedFields = ['name', 'type', 'rating', 'genre', 'description'];

        $extraFields = array_diff(array_keys($request->all()), $allowedFields);

        if (count($extraFields) > 0) {
            return response()->json([
                'message' => 'Invalid fields provided',
                'error' => 'Unrecognized fields: ' . implode(', ', $extraFields)
            ], 400);
        }

        $valid = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:255',
            'type' => 'required|string|in:movie,anime,series,other',
            'rating' => 'nullable|numeric|min:0|max:10',
            'description' => 'required|string|min:5|max:1000',
            'genre' => 'nullable|array',
            'genre.*' => 'nullable|string',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => 'Invalid request',
                'error' => $valid->messages()
            ], 400);
        }

        $msa->update($request->only(['name', 'type', 'rating', 'genre', 'description']));

        return response()->json([
            'message' => 'Item updated successfully',
            'data' => new MsaResource($msa)
        ], 200);
    }


    public function destroy(Msa $msa){
        $user = auth('sanctum')->user();

        if($msa->user_id !== $user->id){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $msa->delete();
        return response()->json([
            'message' => 'Item deleted successfully'
        ], 200);
    }
}
