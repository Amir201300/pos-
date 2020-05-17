<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Manage\BaseController;
use DB,Auth;
use App\Models\color;
use App\Models\Sizes;
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = $request->header('lang');
        $user=Auth::user();
        $color_code=null;
        $color=color::find($this->pivot->color_id);
        $size_name=null;
        if(!is_null($color)){
            $color_code=new ColorResource($color);
        }
        $size=Sizes::find($this->pivot->size_id);
        if(!is_null($size)){
            $size_name=new SizeResource($size);
        }
        return [
            'id' => $this->id,
            'id' => $this->id,
            'name' => $lang == 'ar' ? $this->name_ar : $this->name_en,
            'desc' => $lang == 'ar' ? $this->desc_ar : $this->desc_en,
            'image' => BaseController::getImageUrl('Products',$this->image),
            'rate' => $this->rate,
            'price' => (double)$this->price,
            'is_offer'=>$this->is_offer ? $this->is_offer : 0,
            'offer_amount'=>$this->offer_amount,
            'lat'=>(double)$this->lat,
            'lng'=>(double)$this->lng,
            'is_favorite'=>$this->user_favorite->contains($user->id),
            'color' => $color_code,
            'size'=>$size_name,
            'quantity'=>(int)$this->pivot->quantity,
        ];
    }
}
