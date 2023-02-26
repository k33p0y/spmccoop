<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $client = new Client(['base_uri' => 'http://192.168.254.101:8080']);
            $response = $client->request('POST', '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => '7GDo3Zb23L8TC7QIO7D94GB45MPzEgkMM68OvQJB',
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


}
