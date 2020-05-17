<?php

namespace Shop\Reposatries;

use Shop\Http\Resources\ShopResource;
use Shop\Interfaces\ShopInterface;
use Validator,Auth,Artisan,Hash,File,Crypt;

class ShopReposatry implements ShopInterface {

    use \App\Traits\ApiResponseTrait;

    /**
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function login($request)
    {
        $lang = $request->header('lang');
        $credentials = [

            'name' => $request['name'],
            'password'=>$request['password'],
        ];
        if (Auth::guard('Shop')->attempt($credentials)) {
            $user=Auth::guard('Shop')->user();
            $token=$user->createToken('Shop')->accessToken;
            $user['token']=$token;
            $msg=$lang=='ar' ? 'تم تسجيل الدخول بنجاح' : 'login success';
            return $this->apiResponseData(new ShopResource($user),$msg,200);
        }
        $msg=$lang=='ar' ? 'البيانات المدخلة غير صحيحة' : 'invalid username or password';
        return $this->apiResponseMessage(1,$msg,200);
    }
}