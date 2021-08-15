<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return time();
});

// API route group
$router->group(['prefix' => 'api','middleware' => ['XssSanitizer']], function () use ($router) {
    
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('me','EventController@me');
    $router->post('event', 'EventController@add');
    $router->post('cekevent','EventController@cekEvent');
    $router->post('ubahprofile','EventController@ubahProfile');
    $router->post('pesertawebinar','EventController@pesertawebinar');
    $router->post('setpaid','EventController@setBayar');
    $router->post('submisi','UploadController@submisi');
    $router->post('upload','UploadController@upload');
    $router->get('download/{event_id}','ExportController@download');
 });

// No Auth
$router->group(['prefix' => 'api','middleware' => ['XssSanitizer']], function () use ($router) {

    $router->get('products', 'ProductController@getAll');


});



// With Auth
$router->group(['prefix' => 'api','middleware' => ['XssSanitizer','auth']], function () use ($router) {


    $router->post('products', 'ProductController@addProduct');
    $router->post('wcr', 'ProductController@wcr');

    $router->get('/seller/products', 'ProductController@getSellerProduct');

});