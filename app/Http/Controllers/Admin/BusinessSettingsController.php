<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Model\Language;

class BusinessSettingsController extends Controller
{
    public function store_index()
    {
        return view('admin-views.business-settings.store-index');
    }
    
    public function mail_config(Request $request)
    {
        BusinessSetting::where(['key' => 'mail_config'])->update([
            'value' => json_encode([
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password'],
            ]),
        ]);
        Toastr::success('Configuration updated successfully!');
        return back();
    }
    
    public function mail_index()
    {
        return view('admin-views.business-settings.mail-index');
    }
    
    public function store_language(Request $request)
    {
        if (!empty($request->file('icon'))) {

            $icon = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('languages')) {
                Storage::disk('public')->makeDirectory('languages');
            }

            $icon_img = Image::make($request->file('icon'));
            $icon_img->resize(80, 80);
            $icon_img_stream = $icon_img->stream();
            Storage::disk('public')->put('languages/' . $icon, $icon_img_stream);
        }
        $language = new Language();
        $language->name = $request->name;
        $language->code = $request->code;
        $language->icon = $icon;
        $language->save();
        Toastr::success('Language added successfully!');
        return view('admin-views.languages.index');
    }
    
    public function update_language(Request $request)
    {  
        $language =  Language::find($request->language_id);
        
        if (!empty($request->file('icon'))) {

            $icon = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('languages')) {
                Storage::disk('public')->makeDirectory('languages');
            }
            if (Storage::disk('public')->exists('languages/' . $language['icon'])) {
                Storage::disk('public')->delete('languages/' . $language['icon']);
            }

            $icon_img = Image::make($request->file('icon'));
            $icon_img->resize(80, 80);
            $icon_img_stream = $icon_img->stream();
            Storage::disk('public')->put('languages/' . $icon, $icon_img_stream);
        }
        
        $language->name = $request->name;
        $language->code = $request->code;
        if (!empty($request->file('icon'))) {
           $language->icon = $icon; 
        }
        
        $language->save();
        Toastr::success('Language added successfully!');
        return redirect('admin/business-settings/language/list');
    }
    
    public function language_status(Request $request)
    {
        $language = Language::find($request->id);
        $language->status = $request->status;
        $language->save();
        Toastr::success('Trip status updated!');
        return back();
    }
    
    public function store_setup(Request $request)
    {
       
        DB::table('business_settings')->updateOrInsert(['key' => 'app_name'], [
            'value' => $request['store_name'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency'],
        ]);
        $curr_logo = BusinessSetting::where(['key' => 'logo'])->first();
        if (!empty($request->file('logo'))) {
            $image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('store')) {
                Storage::disk('public')->makeDirectory('store');
            }
            if (isset($curr_logo) && Storage::disk('public')->exists('store/' . $curr_logo['value'])) {
                Storage::disk('public')->delete('store/' . $curr_logo['value']);
            }
            $note_img = Image::make($request->file('logo'))->stream();
            Storage::disk('public')->put('store/' . $image_name, $note_img);
        } else {
            $image_name = $curr_logo['value'];
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name,
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification'],
        ]);
        
        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'price_per_mile'], [
            'value' => $request['price_per_mile'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'price_per_minute'], [
            'value' => $request['price_per_minute'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_version'], [
            'value' => $request['app_version'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'earning_type'], [
            'value' => $request['earning_type'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'minimum_earning_minutes'], [
            'value' => $request['minimum_earning_minutes'],
        ]);

        Toastr::success('Settings updated!');
        return back();
    }
    
    
    public function terms_and_conditions()
    {
        $tnc = BusinessSetting::where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            BusinessSetting::insert([
                'key' => 'terms_and_conditions',
                'value' => '',
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }
    
    public function terms_and_conditions_update(Request $request)
    {
        BusinessSetting::where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc,
        ]);

        Toastr::success('Terms and Conditions updated!');
        return back();
    }
}