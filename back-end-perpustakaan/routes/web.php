<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', ['as' => 'root', function () use ($router) {
    return $router->app->version();
}]);

$router->post('/api/login', 'AuthController@login');
$router->post('/api/register', 'AuthController@register');

Route::group([
    'prefix' => 'api'
], function ($router) {
    $router->get('/author','AuthorController@index');
    $router->get('/publisher','PublisherController@index');
    $router->get('/category','CategoryController@index');
    $router->get('/book','BookController@index');
});


Route::group([
    'middleware'=>'auth',
    'prefix' => 'api'
], function ($router) {
    $router->post('/logout', 'AuthController@logout');
    $router->post('/refresh', 'AuthController@refresh');
    $router->post('/user-profile', 'AuthController@me');

    $router->get('/member','MemberController@index');
    $router->post('/member','MemberController@store');
    $router->put('/member/{id}','MemberController@update');
    $router->get('/member/{id}','MemberController@show');
    $router->delete('/member/{id}','MemberController@destroy');

    
    $router->post('/author','AuthorController@store');
    $router->put('/author/{id}','AuthorController@update');
    $router->get('/author/{id}','AuthorController@show');
    $router->delete('/author/{id}','AuthorController@destroy');

    
    $router->post('/publisher','PublisherController@store');
    $router->put('/publisher/{id}','PublisherController@update');
    $router->get('/publisher/{id}','PublisherController@show');
    $router->delete('/publisher/{id}','PublisherController@destroy');

    $router->get('/transaction','TransactionController@index');
    $router->post('/transaction','TransactionController@store');
    $router->put('/transaction/{id}','TransactionController@update');
    $router->get('/transaction/{id}','TransactionController@show');
    $router->delete('/transaction/{id}','TransactionController@destroy');

    $router->post('/book','BookController@store');
    $router->post('/book/{id}','BookController@update');
    $router->get('/book/{id}','BookController@show');
    $router->delete('/book/{id}','BookController@destroy');

    $router->post('/category','CategoryController@store');
    $router->put('/category/{id}','CategoryController@update');
    $router->get('/category/{id}','CategoryController@show');
    $router->delete('/category/{id}','CategoryController@destroy');

   


});
