<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Model\BusinessSetting;
use App\Model\EmailVerifications;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;
use Carbon\Carbon;
use App\Model\UserVehicleDetail;
use App\Model\UserBankDetail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function verify_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:11|max:14|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json([
            'message' => 'Number is ready to register',
            'otp' => 'inactive'
        ], 200);
    }
    
    public function check_email(Request $request)
    {
        // Check first if it is update password 
        if($request->update_password == 'yes'){
            $token = rand(1000, 9999);
            DB::table('email_verifications')->insert([
                'email' => $request['email'],
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
          Mail::to($request['email'])->send(new EmailVerification($token));
          return response()->json([
                'message' => 'Email is ready to register',
                'token' => 'inactive'
            ], 200); 
        }
        
        // Here if user signup
        $tenMinutesAgo = Carbon::now()->subMinutes(10);
        $email_verifications_count = EmailVerifications::where('email', $request['email'])
        ->whereBetween('created_at', [$tenMinutesAgo, Carbon::now()])
        ->count();
        
        if($email_verifications_count > 8){
            return response()->json(['errors' => [
                 ['code' => 'invalid', 'message' => 'You have sent too many attempts']
              ]], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users'
        ]);
        
       if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
       }else {
           $token = rand(1000, 9999);
            DB::table('email_verifications')->insert([
                'email' => $request['email'],
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
          Mail::to($request['email'])->send(new EmailVerification($token));
          return response()->json([
                'message' => 'Email is ready to register',
                'token' => 'inactive'
            ], 200); 
       }
    }

    public function verify_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $verify = EmailVerifications::where(['email' => $request['email'], 'token' => $request['token']])->first();

        if (isset($verify)) {
            $verify->delete();
            return response()->json([
                'message' => 'Token verified!',
            ], 200);
        }

        return response()->json(['errors' => [
            ['code' => 'token', 'message' => 'Token is not found!']
        ]], 404);
    }

    public function register(Request $request)
    {  
     
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            //'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ], [
            'full_name.required' => 'The first name field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        
        
         if (!empty($request->file('profile_image'))) {
            $profile_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $profile_note_img = Image::make($request->file('profile_image'))->stream();
            Storage::disk('public')->put('profile/' . $profile_image_name, $profile_note_img);
        } else {
            $profile_image_name = null;
        }
        
        if (!empty($request->file('license_image'))) {
            $license_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $license_note_img = Image::make($request->file('license_image'))->stream();
            Storage::disk('public')->put('profile/' . $license_image_name, $license_note_img);
        } else {
            $license_image_name = null;
        }
        
        if (!empty($request->file('insurance_image'))) {
            $insurance_image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $insurance_img = Image::make($request->file('insurance_image'))->stream();
            Storage::disk('public')->put('profile/' . $insurance_image_name, $insurance_img);
        } else {
            $insurance_image_name = null;
        }
        
        $referral_code = Str::random(10);
     
       // Check if the generated code is unique
         while (User::where('referral_code', $referral_code)->exists()) {
           $referral_code = Str::random(10);
        }
        
        $user = New User();
        $user->full_name = $request['full_name'];
        $user->email = $request['email'];
        $user->address = $request['address'];
        $user->phone = $request['phone'];
        $user->image = $profile_image_name;
        $user->social_security_number = $request['social_security_number'];
        $user->referral_code = strtoupper($referral_code);
        $user->password = bcrypt($request->password);
        $user->save();
        
        $vehicle_detail = New UserVehicleDetail();
        $vehicle_detail->user_id = $user->id;
        $vehicle_detail->vehicle_model_name = $request['vehicle_model_name'];
        $vehicle_detail->vehicle_model_year = $request['vehicle_model_year'];
        $vehicle_detail->plate_number = $request['plate_number'];
        $vehicle_detail->driver_insurance_image = $insurance_image_name;
        $vehicle_detail->driver_license_image = $license_image_name;
        $vehicle_detail->save();
        
        $bank_detail = New UserBankDetail();
        $bank_detail->user_id = $user->id;
        $bank_detail->bank_name = $request['bank_name'];
        $bank_detail->account_number = $request['account_number'];
        $bank_detail->routing_number = $request['routing_number'];
        $bank_detail->save();

        $token = $user->createToken('CustomerAuth')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }
    


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            
            $user = User::where('email', $request->email)->first();
            if($user->deleted == 0){
                $token = auth()->user()->createToken('CustomerAuth')->accessToken;
                return response()->json([
                    'token' => $token,
                    'user' => auth()->user(),
                ], 200);
            }else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
            return response()->json([
                'token' => $token,
                'user' => auth()->user(),
            ], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
    public function loginByPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebasetoken' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
            $factory = (new Factory)->withServiceAccount('./key/winji-app-firebase-adminsdk-gl2sl-0fba8621b8.json');
            $auth = $factory->createAuth();
            $idTokenString = $request->get("firebasetoken");
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            //var_dump( $verifiedIdToken->claims()->get("phone_number"));
           // var_dump( $uid);exit;
            $phone = $verifiedIdToken->claims()->get("phone_number");//$request->get("firebasetoken");
            if($phone){
                //check phone exist or not
                $data = [
                    'phone' => $phone,
                ];
                $user = User::where("phone",$phone)->first();
                if ($user) {
                    $user->deleted = 0;
                    $user->is_phone_verified = 1;
                    $user->save();
                    //$user = User::where("phone",$phone)->first();
                    $token = $user->createToken('StoreCustomerAuth')->accessToken;
                    return response()->json(['token' => $token], 200);
                } else {
                    $user = User::create([
                        'f_name' => "",
                        'l_name' => "",
                        'email' => "",
                        'is_phone_verified' => 1,
                        'phone' => $phone,
                        'gift_info' => "no gifts",
                        'password' => ""
                    ]);
                    $token = $user->createToken('storeCustomerAuth')->accessToken;
                    return response()->json(['token' => $token], 200);
                }
            }
        } catch (InvalidToken $e) {
            //echo 'The token is invalid: '.$e->getMessage();
            return response()->json([
                'errors' => 'The token is invalid: '.$e->getMessage()
            ], 401);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'errors' => 'The token could not be parsed: '.$e->getMessage()
            ], 401);
            //echo 'The token could not be parsed: '.$e->getMessage();
        }
    }
    
    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
                $user = User::where("email", $request->email)->first();
                if ($user) {
                    $user->deleted = 0;
                    $user->provider_name = $request->provider_name;
                    $user->provider_id = $request->provider_id;
                    $user->save();
                    $token = $user->createToken('StoreCustomerAuth')->accessToken;
                    return response()->json(['token' => $token], 200);
                } else {
                    
                    $user = User::create([
                        'full_name' => $request->full_name,
                        'email' => $request->email,
                        'password' => "",
                        'provider_name' => $request->provider_name,
                        'provider_id' => $request->provider_id,
                    ]);
                    $token = $user->createToken('storeCustomerAuth')->accessToken;
                    return response()->json(['token' => $token], 200);
                }
        
        } catch (InvalidToken $e) {
            //echo 'The token is invalid: '.$e->getMessage();
            return response()->json([
                'errors' => 'The token is invalid: '.$e->getMessage()
            ], 401);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'errors' => 'The token could not be parsed: '.$e->getMessage()
            ], 401);
            //echo 'The token could not be parsed: '.$e->getMessage();
        }
    }
}

