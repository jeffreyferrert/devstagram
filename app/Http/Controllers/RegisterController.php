<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    //
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //dd($request);
        //dd($request->get('name'));

        //Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        //validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required',
            'password_confirmation' => 'required|min:6' //required|confirmed|min:6
        ]);

        User::create([
           'name' => $request->name,
           'username' => $request->username,
           'email' => $request->email,
           'password' => Hash::make($request->password)
        ]);

        //autenticar usuario
        auth()->attempt([
           'email' => $request->email,
            'password' => $request->password
        ]);

        //otra forma de autenticar
        //auth()->attempt($request->only('email','password'));

        //redireccionando
        return redirect()->route('posts.index',auth()->user()->username);
    }

}
