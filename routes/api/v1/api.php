<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1'], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('register', 'CustomerAuthController@register');
        
        Route::post('login', 'CustomerAuthController@login');
        Route::post('social-login', 'CustomerAuthController@social_login');
        //Route::post('loginbyphone', 'CustomerAuthController@loginByPhone');
        //Route::post('verify-phone', 'CustomerAuthController@verify_phone');

        Route::post('check-email', 'CustomerAuthController@check_email');
        Route::post('verify-email', 'CustomerAuthController@verify_email');

        Route::post('forgot-password', 'PasswordResetController@reset_password_request');
        Route::post('verify-token', 'PasswordResetController@verify_token');
        Route::post('verify-password-token', 'PasswordResetController@verify_password_token');
        Route::post('reset-password', 'PasswordResetController@reset_password_submit');

    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });


    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@get_notifications');
    });
    
    Route::group(['prefix' => 'home'], function () {
        Route::get('featured', 'HomeController@featured_activities');
    });
    
    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('info', 'CustomerController@info');
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');
        Route::post('app-review', 'CustomerController@send_app_review');
        Route::post('register-images', 'CustomerController@register_images');
        Route::post('apply-referral', 'CustomerController@apply_referral_code');
        Route::post('update-status', 'CustomerController@update_status');
        
        Route::group(['prefix' => 'profile'], function () {
            Route::post('personal-info', 'CustomerController@update_personal_info');
            Route::post('vehicle-info', 'CustomerController@update_vehicle_info');
            Route::post('bank-info', 'CustomerController@update_bank_info');
        });
        
       Route::group(['prefix' => 'orders'], function () {
        Route::get('pending-list', 'OrderController@get_pending_list');
        Route::post('update-driver-status', 'OrderController@update_driver_status');
        Route::post('load-items', 'OrderController@load_items');
        
        Route::group(['prefix' => 'route'], function () {
         Route::get('get-routes', 'OrderController@get_order_routes');
         Route::post('delivery-method', 'OrderController@update_route_delivery_method');
         Route::post('status', 'OrderController@route_status');
       });
       });
            // Chatting
        Route::group(['prefix' => 'conversation'], function () {
            Route::get('get', 'ConversationController@messages');
            Route::post('send', 'ConversationController@messages_store');
            Route::post('chat-image', 'ConversationController@chat_image');
            Route::get('messages-history', 'ConversationController@messages_history');
            Route::post('send-image', 'ConversationController@send_image');
        });
        
           // Admin Messages
        Route::group(['prefix' => 'message'], function () {
            Route::get('get', 'MessageController@messages');
        });
    });


    Route::group(['prefix' => 'coupon', 'middleware' => 'auth:api'], function () {
        Route::get('list', 'CouponController@list');
        Route::get('apply', 'CouponController@apply');
    });
});
