<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use Illuminate\Http\Request;
use App\Http\Resources\RoutineResource;

class MyRoutineController extends Controller
{
    public function index(Request $request)
    {
        $routines = $request->user()
            ->routines()
            ->paginate(10);

        return RoutineResource::collection($routines);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'routine_id' => ['required', 'integer', 'exists:routines,id'],
        ]);

        $request->user()
            ->routines()
            ->syncWithoutDetaching([$data['routine_id']]);

        return response()->json(['message' => 'Suscrito correctamente'], 201);
    }

    public function destroy(Request $request, Routine $routine)
    {
        $request->user()->routines()->detach($routine->id);

        return response()->json(['message' => 'Desuscrito correctamente']);
    }
}
