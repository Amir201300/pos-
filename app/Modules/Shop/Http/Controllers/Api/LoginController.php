<?php

namespace Shop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\User;
use Shop\Models\Shop;
use Shop\Interfaces\ShopInterface;

class LoginController extends Controller
{



    public function login(ShopInterface $ShopInterface,Request $request)
    {
        return $ShopInterface->login($request);
    }




}