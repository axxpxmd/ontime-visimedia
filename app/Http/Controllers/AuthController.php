<?php

namespace App\Http\Controllers;

use App\Rules\LoginRule;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'username'  => ['required'],
            'password'  => ['required']
        ]);
        if (!auth()->attempt(['username' => $request->username, 'password' =>  $request->password])) {
            return redirect()->back()->with('error', 'Username / Password Salah.');
        }else{
            if (auth()->user()->role->role == 'Admin') { // do your magic here
                return redirect()->route('kehadiran.index');
            }
            if (in_array(auth()->user()->role->role, ["Eselon 4","Eselon 3","Eselon 2","Eselon 1",])) {
                return redirect()->route('atasanPresents.index');
            }if (auth()->user()->role->role == 'Pegawai'){
                return redirect('daftar-hadir');
            }

            return redirect('profil');
        }

    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
