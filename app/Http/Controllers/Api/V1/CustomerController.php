<?php

namespace App\Http\Controllers\Api\V1;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Model\UserVehicleDetail;
use App\Model\UserBankDetail;

class CustomerController extends Controller
{
    public function info(Request $request)
    {
        $user = User::with('bank_details', 'vehicle_details')->where('id', $request->user()->id)->first();
        return response()->json($user, 200);
    }
    
    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'cm_firebase_token'=>$request['cm_firebase_token']
        ]);

        return response()->json(['message' => 'successfully updated!'], 200);
    }
    
    public function register_images(Request $request)
    {
        if (!empty($request->file('profile_image'))) {
            $profile_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('profile_image'))->stream();
            Storage::disk('public')->put('profile/' . $profile_image_name, $note_img);
        } else {
            $profile_image_name = null;
        }
        
        if (!empty($request->file('license_image'))) {
            $license_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('license_image'))->stream();
            Storage::disk('public')->put('profile/' . $license_image_name, $note_img);
        } else {
            $license_image_name = null;
        }
        
        if (!empty($request->file('insurance_image'))) {
            $insurance_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('insurance_image'))->stream();
            Storage::disk('public')->put('profile/' . $insurance_image_name, $note_img);
        } else {
            $insurance_image_name = null;
        }
        
        $user = User::find($request->user()->id);
        $user->image = $profile_image_name;
        $user->save();
        
        $vehicle_detail = UserVehicleDetail::where('user_id', $request->user()->id)->first();
        $vehicle_detail->driver_insurance_image = $insurance_image_name;
        $vehicle_detail->driver_license_image = $license_image_name;
        $vehicle_detail->save();
        
        return response()->json([
            'token' => 'Images Updated',
        ], 200);
    }
    
    
    public function update_personal_info(Request $request)
    {  
        
         if (!empty($request->file('profile_image'))) {
            $profile_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('profile_image'))->stream();
            Storage::disk('public')->put('profile/' . $profile_image_name, $note_img);
        } else {
            $profile_image_name = null;
        }
        
        $user = User::find($request->user()->id);
        $user->full_name = $request['full_name'];
        $user->email = $request['email'];
        $user->address = $request['address'];
        $user->phone = $request['phone'];
        $user->profile_updated = 1;
        
        if (!empty($request->file('profile_image'))) {
          $user->image = $profile_image_name;
        }
        
        $user->social_security_number = $request['social_security_number'];
        
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return response()->json([
            'user' => $user,
        ], 200);
    }
    
    
    public function update_vehicle_info(Request $request)
    {  
        if (!empty($request->file('license_image'))) {
            $license_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('license_image'))->stream();
            Storage::disk('public')->put('profile/' . $license_image_name, $note_img);
        } else {
            $license_image_name = null;
        }
        
        if (!empty($request->file('insurance_image'))) {
            $insurance_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($request->file('insurance_image'))->stream();
            Storage::disk('public')->put('profile/' . $insurance_image_name, $note_img);
        } else {
            $insurance_image_name = null;
        }
        
        $vehicle_detail = UserVehicleDetail::where('user_id', $request->user()->id)->first();
        $vehicle_detail->vehicle_model_name = $request['vehicle_model_name'];
        $vehicle_detail->vehicle_model_year = $request['vehicle_model_year'];
        $vehicle_detail->plate_number = $request['plate_number'];
        
        if (!empty($request->file('insurance_image'))) {
          $vehicle_detail->driver_insurance_image = $insurance_image_name;  
        }
        
        if (!empty($request->file('license_image'))) {
         $vehicle_detail->driver_license_image = $license_image_name;
        }
        
        $vehicle_detail->save();

        return response()->json([
            'message' => 'success',
        ], 200);
    }
    
    
    public function update_bank_info(Request $request)
    { 
        $bank_detail = UserBankDetail::where('user_id', $request->user()->id)->first();
        $bank_detail->bank_name = $request['bank_name'];
        $bank_detail->account_number = $request['account_number'];
        $bank_detail->routing_number = $request['routing_number'];
        $bank_detail->save();

        return response()->json([
            'message' => 'success',
        ], 200);
    }
    
    public function apply_referral_code(Request $request)
    { 
        $referral_user = User::where('referral_code', $request->code)->first();
        if($referral_user){
        $user = User::where('id', $request->user()->id)->first();
        
        if($user->referral_code_applied == 0){
        $user->referral_code_applied = 1;
        $user->save();
        
        $referral_user = $referral_user->credit + 200;
        $referral_user->save();
        
        return response()->json([
            'message' => 'success',
           ], 200);
        }else{
            return response()->json([
            'message' => 'expired',
           ], 200);
         }
        }else{
            return response()->json([
            'message' => 'failed',
        ], 200);
        }
    }
    
    public function update_status(Request $request)
    { 
        $user = User::find($request->user()->id);
        if($user->status == 1){
            $user->status = 0;
        }else{
            $user->status = 1;
        }
        $user->save();

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}