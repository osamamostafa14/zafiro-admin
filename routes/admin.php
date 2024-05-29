<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:admin']]);



Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::get('staff-login', 'LoginController@staff_login')->name('staff-login');
        Route::post('staff-login', 'LoginController@staff_submit');
        Route::get('home-page', 'LoginController@home_page')->name('home-page');
    });
    /*authentication*/
 /*   Route::group(['namespace' => 'Web', 'prefix' => 'web', 'as' => 'web.'], function () {
        Route::get('home-page', 'LoginController@home_page')->name('home-page');
    });*/
           
          Route::group(['middleware' => ['staffadmin']], function () { 
              Route::get('/admin/staff-dashboard', 'SystemController@staff_dashboard')->name('staff-dashboard');
    });
    
     Route::group(['middleware' => ['auth:admin,staff']], function () {
        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('order', 'ReportController@order_index')->name('order');
            Route::get('earning', 'ReportController@earning_index')->name('earning');
            Route::post('set-date', 'ReportController@set_date')->name('set-date');
            Route::get('analytics', 'ReportController@analytics')->name('analytics');
            Route::get('wishlists/{latest}', 'ReportController@wishlists')->name('wishlists');
            Route::get('statistics', 'ReportController@stats_index')->name('statistics');
        });

        Route::post('broadcasting-auth', function (\Illuminate\Http\Request $request) {
            return Broadcast::auth($request);
        })->middleware('auth:admin')->name('broadcasting-auth');


        Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
            Route::get('list', 'DriverController@list')->name('list');
            Route::get('view/{id}', 'DriverController@view')->name('view');
            Route::post('search', 'DriverController@search')->name('search');
         });

         Route::group(['prefix' => 'earnings', 'as' => 'earnings.'], function () {
            Route::get('list', 'EarningsController@list')->name('list');
            Route::get('view/{id}', 'EarningsController@view')->name('view');
            Route::post('search', 'EarningsController@search')->name('search');
         });

        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::get('add-new', 'OrderController@add_new')->name('add-new');
            Route::post('store', 'OrderController@store')->name('store');
            Route::get('edit/{id}', 'OrderController@edit')->name('edit'); 
            Route::post('update', 'OrderController@update')->name('update');
            Route::get('list', 'OrderController@list')->name('list');
            Route::get('list/{order_status}', 'OrderController@list')->name('list-search');
            Route::post('search', 'OrderController@search')->name('search');
            Route::put('status/{id}', 'OrderController@status')->name('status');
            Route::delete('delete/{id}', 'OrderController@delete')->name('delete');
            Route::put('{id}/update-status','OrderController@updateOrderStatus')->name('update.status');
            
            Route::group(['prefix' => 'routes', 'as' => 'routes.'], function () {

                Route::get('/live-tracking/{id}', 'OrderController@liveTracking')->name('live-tracking');
                Route::get('/get-drivers-live-tracking/{id}', 'OrderController@getDriverLiveTracking')->name('get-driver-live-tracking');

                Route::get('add-new/{order_id}', 'OrderController@add_new_route')->name('add-new');
                Route::get('add-route/{order_id}', 'OrderController@add_route')->name('add-route');
                Route::post('store', 'OrderController@store_routes')->name('store');
                Route::post('store-route', 'OrderController@store_route')->name('store-route');
                Route::get('edit/{order_id}', 'OrderController@edit_routes')->name('edit');
            
                Route::post('editdriver', 'OrderController@edit_driver')->name('editdriver');
                Route::post('update', 'OrderController@update_routes')->name('update');
                Route::get('list', 'OrderController@list_route')->name('list');
                Route::put('status/{id}', 'OrderController@status_route')->name('status');
                Route::delete('delete/{id}', 'OrderController@delete_route')->name('delete');
                
                Route::post('select-route-id', 'OrderController@select_route_id')->name('select-route-id');
                Route::post('route-order', 'OrderController@route_ordering')->name('route-order');
                Route::post('change-current', 'OrderController@change_current')->name('change-current');
                Route::post('store-notes/{routeid}', 'OrderController@store_notes')->name('store-notes'); 
           });
         
            // DRIVER
            Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
            Route::get('assign-driver/{order_id}', 'OrderController@assign_driver')->name('assign-driver');
            Route::post('store-assign', 'OrderController@store_assign')->name('store-assign');
            Route::get('get-drivers-live-tracking/{id}', 'OrderController@getDriverLatLongLiveTracking')->name('get-driver-live-tracking');
            Route::get('details-driver/{driver_id}/{order_id}', 'OrderController@driver_details')->name('driver_details');

         });
        });
        
         Route::group(['prefix' => 'message', 'as' => 'message.'], function () {
            Route::get('view/{driver_id}/{order_id}', 'MessagesController@driver_messages')->name('view');
            Route::post('store', 'MessagesController@store')->name('store');

            Route::get('index/{user_id}', 'MessagesController@index')->name('index'); // this routes
            Route::post('broadcast', 'MessagesController@broadcast')->name('broadcast');
            Route::post('receive', 'MessagesController@receive')->name('receive');
        });
     });
 
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/', 'SystemController@dashboard')->name('dashboard');
        Route::get('settings', 'SystemController@settings')->name('settings');
        Route::post('settings', 'SystemController@settings_update');
        Route::post('settings-password', 'SystemController@settings_password_update')->name('settings-password');
        Route::get('/get-store-data', 'SystemController@store_data')->name('get-store-data');
   

      
        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            Route::get('add-new', 'NotificationController@index')->name('add-new');
            Route::post('store', 'NotificationController@store')->name('store');
            Route::get('edit/{id}', 'NotificationController@edit')->name('edit');
            Route::post('update/{id}', 'NotificationController@update')->name('update');
            Route::get('status/{id}/{status}', 'NotificationController@status')->name('status');
            Route::delete('delete/{id}', 'NotificationController@delete')->name('delete');
            
            Route::get('add-new-filter', 'NotificationController@filter_index')->name('add-new-filter');
              Route::post('specific', 'NotificationController@notifyFilter')->name('specific');
              
              /// send notification message
              Route::get('new-message-notification/{user_id}', 'NotificationController@chat_index')->name('new-message-notification');
              Route::get('new-message-notification-se/{user_id}', 'NotificationController@services_chat_index')->name('new-message-notification-se');
              Route::post('store-msg-notification', 'NotificationController@store_msg_notification')->name('store-msg-notification');
            
          //  Route::get('specific', 'NotificationController@userFilter')->name('specific');
            Route::get('filter-notify', 'NotificationController@userFilterId')->name('filter-notify');
           
        });

       
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
            Route::post('search', 'ReviewsController@search')->name('search');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('store', 'CouponController@store')->name('store');
            Route::get('update/{id}', 'CouponController@edit')->name('update');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
        });
      
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            
      
           Route::get('store-setup', 'BusinessSettingsController@store_index')->name('store-setup');
            Route::post('update-setup', 'BusinessSettingsController@store_setup')->name('update-setup');

            Route::get('fcm-index', 'BusinessSettingsController@fcm_index')->name('fcm-index');
            Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm');

            Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');

            Route::get('mail-config', 'BusinessSettingsController@mail_index')->name('mail-config');
            Route::post('mail-config', 'BusinessSettingsController@mail_config');

            Route::get('payment-method', 'BusinessSettingsController@payment_index')->name('payment-method');
            Route::post('payment-method-update/{payment_method}', 'BusinessSettingsController@payment_update')->name('payment-method-update');

            Route::get('currency-add', 'BusinessSettingsController@currency_index')->name('currency-add');
            Route::post('currency-add', 'BusinessSettingsController@currency_store');
            Route::get('currency-update/{id}', 'BusinessSettingsController@currency_edit')->name('currency-update');
            Route::put('currency-update/{id}', 'BusinessSettingsController@currency_update');
            Route::delete('currency-delete/{id}', 'BusinessSettingsController@currency_delete')->name('currency-delete');

            Route::get('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions')->name('terms-and-conditions');
            Route::post('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions_update');

            Route::get('privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy');
            Route::post('privacy-policy', 'BusinessSettingsController@privacy_policy_update');

            Route::get('about-us', 'BusinessSettingsController@about_us')->name('about-us');
            Route::post('about-us', 'BusinessSettingsController@about_us_update');

            Route::get('location-setup', 'LocationSettingsController@location_index')->name('location-setup');
            Route::post('update-location', 'LocationSettingsController@location_setup')->name('update-location');
            
            Route::get('/app-version', 'BusinessSettingsController@app_version')->name('app-version');
            Route::post('force-update', 'BusinessSettingsController@force_update')->name('force-update');

        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('list', 'CustomerController@customer_list')->name('list');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
            Route::post('search', 'CustomerController@search')->name('search');
        });

      
    });

    Route::get('test/{id}', 'ServiceController@test');
});
