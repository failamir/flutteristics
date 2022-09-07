<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // On Boarding
    Route::post('on-boardings/media', 'OnBoardingApiController@storeMedia')->name('on-boardings.storeMedia');
    Route::apiResource('on-boardings', 'OnBoardingApiController');
});
