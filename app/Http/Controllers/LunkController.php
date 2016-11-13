<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LunkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /* Expecting an json object
    * {
        "lunk" : "This is a new lunk!", The message Hopefully its encrypted in some way
        "expire" : 3600, //How long it will last in minutes
        "views" : 10 //How many times it can be viewed before it will be destroyed
      }
    */
    public function create(Request $request){
      $this->validate($request,[
        'lunk' => 'required',
        'expire' => 'required|integer',
        'views' => 'required|integer'
      ]);

      $lunk = $request->input('lunk');
      $expire = $request->input('expire');
      $views = $request->input('views');

      $lunkId = bin2hex(random_bytes(10));
      Cache::store('redis')->put($lunkId,
        json_encode(array('lunk'=>$lunk,'views'=>$views, 'expire'=>strtotime('+'.$expire . ' minutes')))
       ,$expire
     );

      return response()->json(['status'=>'success','lunkId'=>$lunkId]);
    }

    public function get($lunkId){
      $value = json_decode(Cache::store('redis')->get($lunkId));
      if($value){
        Cache::store('redis')->forget($lunkId);
        if($value->views > 1){
          $value->views -= 1;
          //Getting the time left for the lunk to expire in.
          $expire = floor(($value->expire - time()) / 60);
          Cache::store('redis')->put($lunkId,
            json_encode(array('lunk'=>$value->lunk,'views'=>$value->views, 'expire'=>$value->expire))
           ,$expire
         );
        }
        return response()->json(['status'=>'success', 'lunk' => $value->lunk]);
      }
      return response()->json(['status'=>'error', 'message' => 'This is not the lunk you are looking for']);
    }
}
