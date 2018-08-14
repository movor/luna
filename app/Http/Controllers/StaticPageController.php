<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function index()
    {
        return view('index');
    }
}