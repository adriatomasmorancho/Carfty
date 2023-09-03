<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function viewErrorGeneric()
    {
        return view('errors.genericError');
    }

    public function viewErrorProduct()
    {
        return view('errors.notProductExist');
    }

    public function viewErrorShop()
    {
        return view('errors.notShopExist');
    }
}
