<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Nutritionist;
use App\Model\Currency;

class ConfigController extends Controller
{
    public function configuration()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        $cod = json_decode(BusinessSetting::where(['key' => 'cash_on_delivery'])->first()->value, true);
        $dp = json_decode(BusinessSetting::where(['key' => 'digital_payment'])->first()->value, true);
        
        return response()->json([
            'app_name' => BusinessSetting::where(['key' => 'app_name'])->first()->value,
            'app_logo' => BusinessSetting::where(['key' => 'logo'])->first()->value,
            'app_address' => BusinessSetting::where(['key' => 'address'])->first()->value,
            'app_phone' => BusinessSetting::where(['key' => 'phone'])->first()->value,
            'app_email' => BusinessSetting::where(['key' => 'email_address'])->first()->value,
           
            'base_urls' => [
                'notification_image_url' => asset('storage/app/public/notification'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'chat_image_url' => asset('storage/app/public/chat'),
                'place_image_url' => asset('storage/app/public/place'),
               
            ],
            'currency_symbol' => $currency_symbol,
            'cash_on_delivery' => $cod['status'] == 1 ? 'true' : 'false',
            'digital_payment' => $dp['status'] == 1 ? 'true' : 'false',
            'terms_and_conditions' => BusinessSetting::where(['key' => 'terms_and_conditions'])->first()->value,
            //'privacy_policy' => BusinessSetting::where(['key' => 'privacy_policy'])->first()->value,
          //  'about_us' => BusinessSetting::where(['key' => 'about_us'])->first()->value,
           // 'terms_and_conditions' => route('terms-and-conditions'),
            'privacy_policy' => route('privacy-policy'),
            'about_us' => route('about-us')
        ]);
    }
}
