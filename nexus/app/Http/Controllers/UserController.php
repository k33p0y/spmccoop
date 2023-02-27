<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function prepareRequest(Request $request)
    {
        if ($request->has('name')) { $request->merge(['name' => strip_tags($request->get('name'))]); }
        if ($request->has('username')) { $request->merge(['username' => strip_tags($request->get('username'))]); }
        if ($request->has('employee_id')) { $request->merge(['employee_id' => strip_tags($request->get('employee_id'))]); }
        if ($request->has('email')) { $request->merge(['email' => strip_tags($request->get('email'))]); }
    }

    public function checkUser(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|string'
            ]
        );


        $userExists = User::where('employee_id', $request->id)->exists();

        if($userExists) {
            $user = User::where('employee_id', $request->id)->first();

            $vote = DB::select(
                'SELECT
                    count(*) as count
                FROM spmccoop.votes v
                LEFT JOIN spmccoop.election_details ed ON v.election_detail_id = ed.id
                LEFT JOIN spmccoop.elections e ON ed.election_id = e.id
                WHERE v.voter_user_id = :user_id
                AND e.status = 1',
                [ 'user_id' => $user->id ]
            );

            if($vote[0]->count > 0) {
                return response()->json(['errors' => ['id' => ["Already voted."]]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return response([
                'message' => 'Record found'
            ], Response::HTTP_OK);
        } else {
            return response()->json(['errors' => ['id' => ["ID not found."]]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function index() {
        $users = User::orderBy('name', 'ASC')->get();
        return $users;
    }

    public function store(Request $request) {
        $this->prepareRequest($request);
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,NULL,id',
            'name' => 'required|string|max:255|unique:users,name,NULL,id',
            'employee_id' => 'required|string|max:255|unique:users,employee_id,NULL,id',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => 'required|min:8|max:24',
            'status' => 'required|integer',
            'email' => 'required|email|unique:users,email,NULL,id',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = $request->status;
        $user->candidate = $request->candidate;
        $user->admin = $request->admin;
        $user->employee_id = $request->employee_id;
        $user->code = $this->codeGenerator();
        $user->created_by = $request->user()->id;
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

        return response([
            'message' => 'User registered successfully.'
        ], Response::HTTP_CREATED);
    }



    public function update(Request $request) {
        $this->prepareRequest($request);
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $request->id . ',id',
            'name' => 'required|string|max:255|unique:users,name,' . $request->id . ',id',
            'employee_id' => 'required|string|max:255|unique:users,employee_id,' . $request->id . ',id',
            // 'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            // 'password_confirmation' => 'required|min:8|max:24',
            'status' => 'required|integer',
            'email' => 'required|email|unique:users,email,' . $request->id . ',id',
        ]);

        $user = User::findOrFail($request->id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->password = bcrypt($request->password);
        $user->status = $request->status;
        $user->candidate = $request->candidate;
        $user->admin = $request->admin;
        $user->employee_id = $request->employee_id;
        $user->code = $this->codeGenerator();
        $user->updated_by = $request->user()->id;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();

        return response([
            'message' => 'User record updated successfully.'
        ], Response::HTTP_CREATED);
    }

    function codeGenerator() {
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($pool) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $pool[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
