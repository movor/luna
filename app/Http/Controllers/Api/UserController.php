<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Request;

class UserController extends ApiController
{
    public function current()
    {
        return \Auth::user();
    }

    public function register()
    {
        $request = app('request');
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => Request::get('name'),
            'email' => Request::get('email'),
            'password' => bcrypt(Request::get('password')),
        ]);

        return [];
    }
}