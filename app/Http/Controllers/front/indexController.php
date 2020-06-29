<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class indexController extends Controller
{
    public function index()
    {
        return view('front.index');
    }

}
