<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function prepareRequest(Request $request)
    {
        if ($request->has('username')) { $request->merge(['username' => mb_strtoupper(strip_tags($request->get('username')))]); }
    }

    public function authenticate(Request $request) {
        try {
            $client = new Client(['base_uri' => 'http://192.168.1.118:8080']);
            $response = $client->request('POST', '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => 'VWXXmqczDJsO7K5xJoAUHOKwc8CdPFmoFXdl2aRD',
                    'username' => $request->username,
                    'password' => $request->password
                ],
                'headers' => ['Accept' => 'application/json']
            ]);
            return response(json_decode((string) $response->getBody(), true), $response->getStatusCode());
        }

        catch (BadResponseException $exception) {
            return response()->json(['errors' => ['username' => ["Invalid credentials."]]], Response::HTTP_UNAUTHORIZED);
            // $response = $exception->getResponse();
            // return response(json_decode((string) $response->getBody(), true), $response->getStatusCode());
        }
    }

    public function invalidate() {
        $user = Auth::user()->token();
        $user->revoke();

        return response([
            'message' => 'User token revocation successful.'
        ], Response::HTTP_OK);
    }

    public function changePassword(Request $request) {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required', Password::min(8)->letters()->numbers()],
        ]);

        if (Hash::check($request->current_password, $request->user()->password)) {
            $user = User::findOrFail($request->user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
            return response([
                'message' => 'Password successfully updated.'
            ], Response::HTTP_OK);
        };
        return response()->json(['errors' => ['current_password' => ['Invalid password.']]], 422);
    }

    public function updatePicture(Request $request) {
        $this->validate($request, [
            'profile_picture' => 'nullable|file|mimes:png,jpg,jpeg|max:5120'
        ], [
            'profile_picture.max' => 'The profile picture must not be greater than 5MB.'
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $name = $file->hashName(); // Generate a unique, random name...
            $filename = 'images/' . $name;

            $img = Image::make($file->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream();
            Storage::disk('local')->put($filename, $img, 'public');

            $user = User::findOrFail(Auth::id());
            $user->profile_picture = $filename;
            $user->save();

            return response([
                'message' => 'Password successfully updated.'
            ], Response::HTTP_OK);
        }

        return response()->json(['errors' => ['profile_picture' => ['Invalid.']]], 422);
    }



    public function profileBase64() {
        $user = User::findOrFail(Auth::id());

        $filename = explode($user->profile_picture, '.');

        $data = "data:image/" . $filename[0] . ";base64,". base64_encode(Storage::get($user->profile_picture));
        // return $data;

        return response($data, Response::HTTP_OK);
    }



    public function candidateProfileBase64(Request $request, $id) {

        $user = DB::select('SELECT count(*) as count FROM public.users WHERE id = :id AND profile_picture IS NOT NULL', [ 'id' => $id ]);
        // dd($user[0]);
        if($user[0]->count) {
            $user = User::findOrFail($id);
            $data = "data:image/" . substr($user->profile_picture, -3) . ";base64,". base64_encode(Storage::get($user->profile_picture));

            return response($data, Response::HTTP_OK);
        }
        $data = "data:image/png;base64,". base64_encode(Storage::get('images/account.png'));
        return response($data , Response::HTTP_OK);

    }
}
