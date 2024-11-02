<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubEventController extends Controller
{
    public function create(){
        return view("subevents.create");
    }

    public function store(){
        
    }
}
