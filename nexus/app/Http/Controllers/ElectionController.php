<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ElectionController extends Controller
{
    public function prepareRequest(Request $request)
    {
        if ($request->has('name')) {
            $request->merge(['name' => strip_tags($request->get('name'))]);
        }
    }

    public function index()
    {
        $elections = Election::orderBy('created_at', 'DESC')->get();
        return $elections;
    }

    public function store(Request $request)
    {
        $this->prepareRequest($request);
        $this->validate($request, [
            'name' => 'required|string|unique:elections,name,NULL,id',
            'status' => 'required|integer'
        ], [
            'name.unique' => 'This election event has been already in the database.'
        ]);

        DB::select('UPDATE elections SET status = 0');

        $election = new Election();
        $election->name = $request->name;
        $election->status = $request->status;
        $election->created_by = Auth::user()->id;
        $election->save();

        return response([
            'message' => 'Election event created successfully.'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        $this->prepareRequest($request);
        $this->validate($request, [
            'name' => 'required|string|unique:elections,name,' . $request->id . ',id',
            'status' => 'required|integer'
        ], [
            'name.unique' => 'This election event has been already in the database.'
        ]);

        DB::select('UPDATE elections SET status = 0');

        $election = Election::findOrFail($request->id);
        $election->name = $request->name;
        $election->status =$request->status;;
        $election->updated_by = Auth::user()->id;
        $election->save();

        return response([
            'message' => 'Election event created successfully.'
        ], Response::HTTP_CREATED);
    }

    // public function changeStatus(Request $request)
    // {
    //     $this->prepareRequest($request);
    //     $this->validate($request, [
    //         'name' => 'required|string|unique:elections,name,' . $request->id . ',id'
    //     ], [
    //         'name.unique' => 'This election event has been already in the database.'
    //     ]);

    //     $election = Election::findOrFail($request->id);
    //     $election->status = $election->status == 1 ? 0 : 1;
    //     $election->updated_by = Auth::user()->id;
    //     $election->save();

    //     return response([
    //         'message' => 'Election event created successfully.'
    //     ], Response::HTTP_OK);
    // }

    // public function destroy(Request $request)
    // {
    //     $election = Election::findOrFail($request->id);
    //     $election->delete();

    //     return response([
    //         'message' => 'Election event created successfully.'
    //     ], Response::HTTP_OK);
    // }
}
