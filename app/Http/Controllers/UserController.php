<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Country;
use App\Models\User;
use App\Rules\PasswordRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected bool $stopOnFirstFailure = true;



    public function add(Request $request): JsonResponse
    {
        try{

            $data = $request->validate([
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users',
                'password' => ['required', new PasswordRule],
                'country' => 'required|exists:country,id',
                'birthdate' => 'required|date|before:18 years ago'
            ]);

            User::create($data);

            return response()->json('user created');
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'code' => 'required',
            ]);

            $user = new User();
            $user->verifyEmail($request->code);

            return response()->json();

        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    public function get(): JsonResponse
    {
        try{
            $response = User::query()
                ->where('created_at', '>', Carbon::now()->subMinutes(5))
                ->whereNotNull('email_verified_at')
                ->orderBy('created_at','DESC')
                ->with('Country')
                ->get();

            return response()->json(new UserResource($response));
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    public function country(): JsonResponse
    {
        try{
            $response = Country::query()
                ->orderBy('created_at','DESC')
                ->get();

            return response()->json($response);
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }
}
