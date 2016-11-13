<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Cache;

$app->get('/', function () use ($app) {
    // phpinfo();
    //$client = new \Predis\Client();
    ///$client->set('foo', 'bar');
    //return 'foo stored as ' . $client->get('foo');
    //Cache::store('redis')->put('bar', 'baz' ,10);
    return response()->json(['status'=>'success','message'=>'Welcome to lunk. View the docs here']);

});

$app->get('go', function () use ($app){
  return response()->json(['status'=>'success','message'=>'GO GO GO!']);
});

$app->post('lunk', 'LunkController@create');

$app->get('lunk/{id}','LunkController@get');
