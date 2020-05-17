<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\User;
use App\Http\Controllers\Manage\BaseController;
use App\Http\Controllers\Manage\EmailsController;

class UserController extends Controller
{
    use ApiResponseTrait;

    /*
     * @pram request array
     * @return  response()->json
     */

    public function register(Request $request)
    {
        $lang = $request->header('lang');


        $input = $request->all();
        $validationMessages = [
            'frist_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسمك الاول' :"frist name is required" ,
            'password.required' => $lang == 'ar' ? 'من فضلك ادخل كلمة السر' :"password is required"  ,
            'password.confirmed' => $lang == 'ar' ? 'كلمتا السر غير متطابقتان' :"The password confirmation does not match"  ,
            'email.required' => $lang == 'ar' ? 'من فضلك ادخل البريد الالكتروني' :"email is required"  ,
            'email.unique' => $lang == 'ar' ? 'هذا البريد الالكتروني موجود لدينا بالفعل' :"email is already teken" ,
            'email.regex'=>$lang=='ar'? 'من فضلك ادخل بريد الكتروني صالح' : 'The email must be a valid email address',
            'phone.required' => $lang == 'ar' ? 'من فضلك ادخل البريد رقم الهاتف' :"phone is required"  ,
            'phone.unique' => $lang == 'ar' ? 'رقم الهاتف موجود لدينا بالفعل' :"phone is already teken" ,
            'last_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسم العائلة' :"last name name is required" ,
            'phone.min' => $lang == 'ar' ?  'رقم الهاتف يجب ان لا يقل عن 7 ارقام' :"The phone must be at least 7 numbers" ,
            'phone.numeric' => $lang == 'ar' ?  'رقم الهاتف يجب ان يكون رقما' :"The phone must be a number" ,


        ];

        $validator = Validator::make($input, [
            'frist_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users|min:7|numeric',
            'email' => 'required|unique:users|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required|confirmed',
        ], $validationMessages);

        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 400);
        }

        $user = new User();
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->frist_name = $request->frist_name;
        $user->last_name = $request->last_name;
        $user->lat  = $request->lat;
        $user->lng  = $request->lng;
        $user->status  = 0;
        $user->social  = 0;
        $user->lang=$lang;
        $user->password = Hash::make($request->password);
        if($request->image){
            $name=BaseController::saveImage('users',$request->file('image'));
            $user->image=$name;
        }
        $user->save();
        $token = $user->createToken('TutsForWeb')->accessToken;
        $user['token']=$token;
        $user['image']=BaseController::getImageUrl('users',$user->image);
        EmailsController::verify_email($user->id,$lang);
        $msg=$lang == 'ar' ? 'تم التسجيل بنجاح' : 'register success';
        return response()->json([ 'status'=>1,'message'=> $msg, 'data'=>$user]);

    }

    /*
    *Register Social
     * By Email
    */

    public function register_social(Request $request)
    {
        $lang = $request->header('lang');
        $input = $request->all();
        $validationMessages = [
            'frist_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسمك الاول' :"frist name is required" ,
            'password.required' => $lang == 'ar' ? 'من فضلك ادخل كلمة السر' :"password is required"  ,
            'password.confirmed' => $lang == 'ar' ? 'كلمتا السر غير متطابقتان' :"The password confirmation does not match"  ,
            'email.required' => $lang == 'ar' ? 'من فضلك ادخل البريد الالكتروني' :"email is required"  ,
            'email.unique' => $lang == 'ar' ? 'هذا البريد الالكتروني موجود لدينا بالفعل' :"email is already teken" ,
            'email.regex'=>$lang=='ar'? 'من فضلك ادخل بريد الكتروني صالح' : 'The email must be a valid email address',
            'last_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسم العائلة' :"last name name is required" ,
            'phone.min' => $lang == 'ar' ?  'رقم الهاتف يجب ان لا يقل عن 7 ارقام' :"The phone must be at least 7 numbers" ,
            'phone.numeric' => $lang == 'ar' ?  'رقم الهاتف يجب ان يكون رقما' :"The phone must be a number" ,
        ];

        $validator = Validator::make($input, [
            'frist_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|regex:/(.+)@(.+)\.(.+)/i',
        ], $validationMessages);

        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 400);
        }

        $user = new User();
        $user->email = $request->email;
        $user->lat  = $request->lat;
        $user->lng  = $request->lng;
        $user->frist_name = $request->frist_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->status  = 0;
        $user->social  = 1;
        $user->lang=$lang;
        if($request->image){
            $name=BaseController::saveImage('users',$request->file('image'));
            $user->image=$name;
        }
        $user->save();
        $token = $user->createToken('TutsForWeb')->accessToken;
        $user['token']=$token;
        $user['image']=BaseController::getImageUrl('users',$user->image);
        $msg=$lang == 'ar' ? 'تم التسجيل بنجاح' : 'register success';
        return response()->json([ 'status'=>1,'message'=> $msg, 'data'=>$user]);

    }
    /*
     * login method
     * return user opject and token
     */

    public function login(Request $request)
    {
        $lang = $request->header('lang');

        $user=User::where('phone',$request->eamilOrPhone)->first();
        if(is_null($user))
        {
            $user=User::where('email',$request->eamilOrPhone)->first();
            if(is_null($user))
            {
                $msg=$lang=='ar' ?  'البيانات المدخلة غير موجودة لدينا ':'username not exist' ;
                return $this->apiResponseMessage( 0,$msg, 404);
            }
        }
        $password=Hash::check($request->password,$user->password);
        if($password==true){
            $token = $user->createToken('TutsForWeb')->accessToken;
            $user['token']=$token;
            $user['image']=BaseController::getImageUrl('users',$user->image);
            $msg=$lang=='ar' ? 'تم تسجيل الدخول بنجاح':'login success' ;
            return response()->json([ 'status'=>1,'message'=> $msg, 'data'=>$user]);
        }

        $msg=$lang=='ar' ?  'كلمة السر غير صحيحة' :'Password is not correct' ;
        return $this->apiResponseMessage( 0,$msg, 400);

    }

    /*
     * Social login
     */
    public function login_social(Request $request)
    {
        $lang = $request->header('lang');
        $user=User::where('email',$request->email)->where('social',1)->first();
        if(is_null($user))
        {
            $msg=$lang=='ar' ?  'البريد الالكتروني غير موجود':'Email not exist' ;
            return $this->apiResponseMessage( 0,$msg, 401);
        }
        $token = $user->createToken('TutsForWeb')->accessToken;
        $user['token']=$token;
        $user['image']=BaseController::getImageUrl('users',$user->image);
        $msg=$lang=='ar' ?  'تم تسجيل الدخول بنجاح' :'login success' ;
        return response()->json([ 'status'=>1,'message'=> $msg, 'data'=>$user]);
    }

    /*
     * Change Password
     * @pram old passsword , newpassword
     */
    public function change_password(Request $request)
    {
        $lang = $request->header('lang');
        $user = Auth::user();
        $check=$this->not_found($user,'العضو','user',$lang);
        if(isset($check))
        {
            return $check;
        }
        if(!$request->newPassword){
            $msg=$lang=='ar' ? 'يجب ادخال كلمة السر الجديدة' : 'new password is required';
            return $this->apiResponseMessage(0,$msg,200);
        }
        $password=Hash::check($request->oldPassword,$user->password);
        if($password==true){
            $user->password=Hash::make($request->newPassword);
            $user->save();
            $msg=$lang=='ar' ? 'تم تغيير كلمة السر بنجاح' : 'password changed successfully';
            return $this->apiResponseMessage( 1,$msg, 200);

        }else{
            $msg=$lang=='ar' ? 'كلمة السر القديمة غير صحيحة' : 'invalid old password';
            return $this->apiResponseMessage(0,$msg, 401);

        }
    }
    /*
     * Edit user
     * @pram old passsword , newpassword
    */


    public function edit_profile(Request $request)
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
            'frist_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسمك الاول' :"frist name is required" ,
            'email.required' => $lang == 'ar' ? 'من فضلك ادخل البريد الالكتروني' :"email is required"  ,
            'email.unique' => $lang == 'ar' ? 'هذا البريد الالكتروني موجود لدينا بالفعل' :"email is already teken" ,
            'email.regex'=>$lang=='ar'? 'من فضلك ادخل بريد الكتروني صالح' : 'The email must be a valid email address',
            'phone.required' => $lang == 'ar' ? 'من فضلك ادخل البريد رقم الهاتف' :"phone is required"  ,
            'phone.unique' => $lang == 'ar' ? 'رقم الهاتف موجود لدينا بالفعل' :"phone is already teken" ,
            'last_name.required' => $lang == 'ar' ?  'من فضلك ادخل رقم اسم العائلة' :"last name name is required" ,
            'phone.min' => $lang == 'ar' ?  'رقم الهاتف يجب ان لا يقل عن 7 ارقام' :"The phone must be at least 7 numbers" ,
            'phone.numeric' => $lang == 'ar' ?  'رقم الهاتف يجب ان يكون رقما' :"The phone must be a number" ,

        ];

        $validator = Validator::make($input, [
            'phone' => 'required|min:7|numeric|unique:users,phone,'.$id,
            'email' => 'required|unique:users,email,'.$id.'|regex:/(.+)@(.+)\.(.+)/i',
            'frist_name' => 'required',
            'last_name'=>'required'
        ], $validationMessages);
        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 400);
        }


        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->lat  = $request->lat;
        $user->lng  = $request->lng;
        $user->frist_name = $request->frist_name;
        $user->last_name = $request->last_name;
        if($request->image){
            BaseController::deleteFile('users',$user->image);
            $name=BaseController::saveImage('users',$request->file('image'));
            $user->image=$name;
        }
        $user->save();
        $user['token']=null;
        $msg=$lang=='ar' ?  'تمت العملية بنجاح' :'success' ;
        return $this->apiResponseData(  new UserResource($user),  $msg);
    }

    /*
     * upoad image for user
     */
    public function save_image(Request $request)
    {
        $user=Auth::user();
        $lang=$request->header('lang');
        if($request->image){
            BaseController::deleteFile('users',$user->image);
            $name=BaseController::saveImage('users',$request->file('image'));
            $user->image=$name;
        }else{
            $msg=$lang=='ar' ? 'من فضلك ارفع الصورة' : 'please upload image';
            return $this->apiResponseMessage(0,$msg,200);
        }
        $user->save();
        $msg=$lang=='ar' ? 'تم رفع الصورة بنجاح' : 'image uploaded successfully';
        return $this->apiResponseData(new UserResource($user),$msg,200);

    }

    /*
     * decrypt id and check
     * use Crypt::encrypt
     */

        public function check_virfuy($user_id)
        {
            $id=Crypt::decrypt($user_id);
            $user=User::find($id);
            if(is_null($user)){
                return false;
            }
            $user->status=1;
            $user->save();
            return 'تم تفعيل الاكونت بنجاح';
        }


        /*
         * my wishlist
         * array of products from product resource
         */
        public function my_wishlist(Request $request){
            $lang = $request->header('lang');
            $user=Auth::user();
            $msg=$lang=='ar' ?  'تمت العملية بنجاح' :'success' ;
            return $this->apiResponseData(ProductResource::collection($user->my_wishlist),$msg,200);
        }


    /*
     * my cart
     * array of products from product resource
     */
    public function my_cart(Request $request){
        $lang = $request->header('lang');
        $user=Auth::user();
        $price=BaseController::get_total_price($user->my_cart);
        $msg=$lang=='ar' ?  'تمت العملية بنجاح' :'success' ;
        return response()->json(['data'=>ShopResource::collection($user->my_cart),'message'=>$msg,'total_price'=>$price,'status'=>1]);
    }

    /*
     * @pram notification , language , message , currency
     * 1 ==> on , 0 or null ==>off
     */
    public function settings(Request $request){
        $lang = $request->header('lang');
        $user=Auth::user();
        $user->lang=$request->lang;
        $user->message=$request->message;
        $user->notification=$request->notification;
        $user->currency_id=$request->currency_id;
        $user->save();
        $msg=$lang=='ar' ?  'تم حفظ الاعدادات بنجاح' :'settings saved successfully' ;
        return $this->apiResponseMessage(1,$msg, 200);
    }

    /*
     * get user information from token auth
     */
    public function my_info(Request $request)
    {
        $lang = $request->header('lang');
        $user=Auth::user();
        $msg=$lang=='ar' ?  'تمت العملية بنجاح' :'success' ;
        return $this->apiResponseData(new UserResource($user),$msg);
    }


    /*
     *@pram Email to check exist in database
     *@return  if exist send code to email , not exist sent error message
     */

    public function forget_password(Request $request){
        $lang=$request->header('lang');
        $user=User::where('email',$request->email)->first();
        $check=$this->not_found($user,'البريد الالكتروني','Email Address',$lang);
        if(isset($check)){
            return $check;
        }
        $code=mt_rand(999,9999);
        $user->code=$code;
        $user->save();
        EmailsController::forget_password($user,$lang);
        $msg=$lang=='ar' ? 'تفحص بريدك الالكتروني' : 'check your mail';
        return $this->apiResponseMessage(1,$msg,200);
    }

    /*
     * @pram code , new password
     * @return if code incorrect error message , elseif correct change password successfully
     */
    public function reset_password(Request $request)
    {
        $lang=$request->header('lang');
        if(!$request->code){
            $msg=$lang=='ar' ? 'من فضلك ادخل الكود' : 'code is required';
            return $this->apiResponseMessage(0,$msg,200);
        }
        $user=User::where('code',$request->code)->first();
        if(is_null($user)){
            $msg=$lang=='ar' ? 'الكود غير صحيح' : 'code is incorrect';
            return $this->apiResponseMessage(0,$msg,200);
        }
        if(!$request->password){
            $msg=$lang=='ar' ? 'من فضلك ادخل كلمة السر الجديدة' : 'new password is required';
            return $this->apiResponseMessage(0,$msg,200);
        }
        $user->password=Hash::make($request->password);
        $user->code=null;
        $user->save();
        $msg=$lang=='ar' ? 'تم تغيير كلمة السر بنجاح' : 'password changed successfully';
        return $this->apiResponseMessage(1,$msg,200);
    }
}