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
        return $this->apiResponseMessage(0,$msg,200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo($request)
    {
        $lang = $request->header('lang');
        $user=Auth::user();
        $user['token']=null;
        $msg=$lang=='ar' ? 'تمت العملية بنجاح' : 'success';
        return $this->apiResponseData(new ShopResource($user),$msg,200);
    }

    public function edit_inf($request)
    {
        $lang = $request->header('lang');
        $user = Auth::user();

        $check=$this->not_found($user,'العضو','user',$lang);
        if(isset($check))
        {
            return $check;
        }
        $id=Auth::user()->id;

        $input = $request->all();
        $validationMessages = [
            'name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسمك الاول' :"frist name is required" ,
            'email.required' => $lang == 'ar' ? 'من فضلك ادخل البريد الالكتروني' :"email is required"  ,
            'email.unique' => $lang == 'ar' ? 'هذا البريد الالكتروني موجود لدينا بالفعل' :"email is already teken" ,
            'email.regex'=>$lang=='ar'? 'من فضلك ادخل بريد الكتروني صالح' : 'The email must be a valid email address',
            'phone.required' => $lang == 'ar' ? 'من فضلك ادخل البريد رقم الهاتف' :"phone is required"  ,
            'name.unique' => $lang == 'ar' ? 'اسم المستخدم موجود لدينا بالفعل' :"username is already teken" ,
            'phone.min' => $lang == 'ar' ?  'رقم الهاتف يجب ان لا يقل عن 7 ارقام' :"The phone must be at least 7 numbers" ,
            'phone.numeric' => $lang == 'ar' ?  'رقم الهاتف يجب ان يكون رقما' :"The phone must be a number" ,

        ];

        $validator = Validator::make($input, [
            'phone' => 'required|numeric|min:7',
            'email' => 'required|unique:shops,email,'.$id.'|regex:/(.+)@(.+)\.(.+)/i',
            'name' => 'required|unique:shops,name,'.$id,
        ], $validationMessages);
        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 400);
        }


        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->name = $request->name;
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        if($request->logo){
            deleteFile('Shop',$user->logo);
            $name=saveImage('Shop',$request->file('logo'));
            $user->logo=$name;
        }
        $user->save();
        $user['token']=null;
        $msg=$lang=='ar' ?  'تمت العملية بنجاح' :'success' ;
        return $this->apiResponseData(new ShopResource($user),$msg,200);
    }
}