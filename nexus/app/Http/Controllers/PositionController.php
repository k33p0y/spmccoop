<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    public function prepareRequest(Request $request)
    {
        if ($request->has('name')) { $request->merge(['name' => strip_tags($request->get('name'))]); }
    }

    public function index() {
        $positions = Position::orderBy('created_at', 'DESC')->get();
        return $positions;
    }

    public function store(Request $request) {
        $this->prepareRequest($request);

        $position = new Position();
        $position->name = $request->name;
        $position->count = $request->count;
        $position->created_by = Auth::user()->id;
        $position->save();

        return response([
            'message' => 'Position created successfully.'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request) {
        $this->prepareRequest($request);

        $position = Position::findOrFail($request->id);
        $position->name = $request->name;
        $position->count = $request->count;
        $position->updated_by = Auth::user()->id;
        $position->save();

        return response([
            'message' => 'Position updated successfully.'
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request) {
        $position = Position::findOrFail($request->id);
        $position->delete();

        return response([
            'message' => 'Election event created successfully.'
        ], Response::HTTP_OK);
        
    }
}
