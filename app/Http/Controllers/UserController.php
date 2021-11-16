<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\ResponseService;
use App\Transformers\User\UserResource;
use App\Transformers\User\UserResourceCollection;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = $this
                ->user
                ->login($credentials);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('users.login', null, $e);
        }
        return response(['status' => true, 'message' => $token], 200);
    }

    public function logout(Request $request)
    {
        try {
            $this
                ->user
                ->logout($request->input('token'));
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('users.logout', null, $e);
        }

        return response(['status' => true, 'message' => 'Deslogado com sucesso'], 200);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this
                ->user
                ->create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                ]);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('users.store', null, $e);
        }

        return new UserResource($user, array('type' => 'store', 'route' => 'users.store'));
    }
}
