
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'home',
]);

Route::get('/alert', function(){
   return redirect() ->route('home')->with('info', 'You have signed up');
});
/* Authentication */
Route::get('/signup' , [
    'uses' => 'AuthController@getSignup',
    'as' => 'auth.signup'
]);

Route::post('/signup', [
   'uses' => 'AuthController@postSignup',
]);

Route::get('/signin' , [
    'uses' => 'AuthController@getSignin',
    'as' => 'auth.signin'
]);

Route::post('/signin', [

    'uses' => 'AuthController@postSignin',
]);

Route::get('/signout', [

    'uses' => 'AuthController@getSignout',
    'as' => 'auth.signout'
]);

