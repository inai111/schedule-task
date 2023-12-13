<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'password'=>'confirmed|min:6|max:8|required',
            'email'=>'unique:users|email|required',
            'username'=>'unique:users|alpha_num|required',
            'name'=>'string|required',
        ]);
        User::create($validation);
        return redirect()->back()->with('message','Account created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ],$request->input());

        if(auth()->attempt(Arr::only($validate,['username', 'password']))){
            $request->session()->regenerate();
            return redirect()->intended();
        }
        return redirect()->back()->withErrors(['message' => 'Email or password is invalid'])->withInput();
    }
}
