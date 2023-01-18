<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;

class homeController extends Controller
{
    public function index()
    {
        return view('site.index');
    }
}
