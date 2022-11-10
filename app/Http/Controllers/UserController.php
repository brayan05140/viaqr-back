<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Qrs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $response['status'] = 500;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $response['status'] = 400;
                $response['msm'] = 'invalid_credentials';
            }else{
                $response['token'] = $token;
                $response['status'] = 200;
                $response['msm'] = 'login sucess';
            }
        } catch (JWTException $e) {
            $response['msm'] = 'could_not_create_token';
        }

        return response()->json(compact('response'), $response['status']);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function createQr(Request $request)
    {
        $response['status'] = 400;
        try {
            $validator = Validator::make($request->all(), [
                'nameSignal' => 'required|string|max:255',
                'urlImage' => 'required|string|max:255',
                'descriptionSignal' => 'required|string',
            ]);
    
            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }
    
            $qr = Qrs::create([
                'nameSignal' => $request->get('nameSignal'),
                'urlImage' => $request->get('urlImage'),
                'descriptionSignal' => $request->get('descriptionSignal'),
            ]);

            $response['status'] = 200;
            $response['msm'] = 'Register create';
            
            return response()->json($response);

        } catch (\Throwable $th) {
            $response['msm'] = $th->getMessage();
        }
    }

    public function getDataQr()
    {
        $response['status'] = 400;
        try {
    
            $qrs = Qrs::select()->get()->toArray();

            $response['status'] = 200;
            $response['msm'] = 'operation success';
            $response['data'] = $qrs;    
            
        } catch (\Throwable $th) {
            $response['msm'] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function getDataUsers()
    {
        $response['status'] = 400;
        try {
    
            $users = User::select()->get()->toArray();

            $response['status'] = 200;
            $response['msm'] = 'operation success';
            $response['data'] = $users;

        } catch (\Throwable $th) {
            $response['msm'] = $th->getMessage();
        }

        return response()->json($response);
    }

    public function getDataQrOne(Request $request)
    {
        $response['status'] = 400;
        try {
            $qrs = Qrs::select()->where('id', $request->get('id'))->get()->toArray();

            $response['status'] = 200;
            $response['msm'] = 'operation success';
            $response['data'] = $qrs;    
            
        } catch (\Throwable $th) {
            $response['msm'] = $th->getMessage();
        }

        return response()->json($response);
    }
}
