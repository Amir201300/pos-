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


    /**
     * @param ShopInterface $ShopInterface
     * @param Request $request
     * @return mixed
     */
    public function login(ShopInterface $ShopInterface,Request $request)
    {
        return $ShopInterface->login($request);
    }

    /**
     * @param ShopInterface $ShopInterface
     * @param Request $request
     * @return mixed
     */
    public function get_info(ShopInterface $ShopInterface,Request $request)
    {
        return $ShopInterface->getInfo($request);
    }

    /**
     * @param ShopInterface $ShopInterface
     * @param Request $request
     * @return mixed
     */
    public function edit_info(ShopInterface $ShopInterface,Request $request)
    {
        return $ShopInterface->edit_inf($request);
    }
}