<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function privacy()
    {
        return view('legal.privacy');
    }

    public function impressum()
    {
        return view('legal.impressum');
    }

    public function contact()
    {
        return view('legal.contact');
    }
}