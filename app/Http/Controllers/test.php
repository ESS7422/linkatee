<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class test extends Controller
{

function get() {
    $users = User::all();

    $userData = [];

    foreach ($users as $user) {
        $userData[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // add any additional fields you need
        ];
    }

    return $userData;
}

}
