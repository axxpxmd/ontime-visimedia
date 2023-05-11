<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function support()
    {
        return view('support');
    }

    public function apps()
    {
        return view('apps.index');
    }

    public function ontime()
    {
        return view('apps.OnTime.manifest');
    }
}
