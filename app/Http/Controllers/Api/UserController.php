<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;

class UserController extends ApiController
{


    /**
     * validate , try to login , send response
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = [
            'email' => $request->post('email'),
            'password' => $request->post('password'),
        ];

        // check if login fails
        if (!Auth::attempt($credentials)) {
            //  set http code to 404 , response with error message
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                ->responseWithError("these credentials do not match our records :'(");
        }

        // get user info
        $user = Auth::user();
        // set response data
        $responseData['name'] = $user->name;
        // create new access token using passport personal
        $responseData['token'] = $user->createToken($user->name)->accessToken;


        //  set http code to 200 , response with data and success message
        return $this->setStatusCode(HttpResponse::HTTP_OK)
            ->responseWithData($responseData, 'Welcome Back :)');
    }

    /**
     * validate , register , create access token for user
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRegisterRequest $request)
    {
        // create user
        $user = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => bcrypt($request->post('password'))
        ]);

        // set response data
        $responseData['name'] = $user->name;
        // create and return access token from laravel passport  ( personal )
        $responseData['token'] = $user->createToken($user->name)->accessToken;

        // response with success message and access token
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)
            ->responseWithData($responseData, 'user created successfully <3');
    }

    /**
     * get user info depend on his access token
     *
     * @return \Illuminate\Http\Response
     */
    public function detailsByToken()
    {
        // get authenticated user info
        $user = Auth::user();
        // set response data
        $responseData['name'] = $user->name;
        $responseData['email'] = $user->email;

        // set http code to 200 then response with data
        return $this->setStatusCode(HttpResponse::HTTP_OK)->responseWithData($responseData);
    }
}
