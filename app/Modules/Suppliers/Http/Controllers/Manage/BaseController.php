<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth,File;
use App\Models\Produc_view_user;

class BaseController extends Controller
{
    //site url
    public  static function get_url(){
        return 'http://api.shoohna.com';
    }

    public  static function getImageUrl($folder,$image){
        if($image)
            return BaseController::get_url() . '/images/'.$folder .'/'.$image;
        return BaseController::get_url() . '/images/1.png';

    }


    public static function saveImage($folder,$file)
    {
        $image = $file;
        $input['image'] = mt_rand(). time().'.'.$image->getClientOriginalExtension();
        $dist = public_path('/images/'.$folder.'/');
        $image->move($dist, $input['image']);
        return $input['image'];

    }
    /*
     * TO Delete File From server storage
     */
    public static function deleteFile($folder,$file)
    {
        $file = public_path('/images/'.$folder.'/'.$file);
        if(file_exists($file))
        {
            File::delete($file);
        }
    }

    /*
     * to save new veiw product
     */
    public static function product_view($product_id,$user_id)
    {
        $Produc_view_user=Produc_view_user::where('product_id',$product_id)->where('user_id',$user_id)->first();
        if(is_null($Produc_view_user)){
            $Produc_view_user=new Produc_view_user;
            $Produc_view_user->user_id=$user_id;
            $Produc_view_user->product_id=$product_id;
            $Produc_view_user->count=1;
            $Produc_view_user->save();
        }else {
            $Produc_view_user->count += 1;
            $Produc_view_user->save();
        }
    }


    /*
     *@var array of my cart product
     * return total price
     */

    public  static function get_total_price($product)
    {
        $price=0;
//        foreach($product as $row){
//            $price+= $row->price *$row->pivot->quantity;
//        }
//        return number_format((double)$price, 2);
        return 5;
    }
}