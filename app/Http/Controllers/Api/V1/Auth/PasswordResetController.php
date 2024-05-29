<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Model\PasswordReset;
use Carbon\Carbon;
use App\Model\EmailVerifications;

class PasswordResetController extends Controller
{
    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $customer = User::Where(['email' => $request['email']])->first();
        if($customer->deleted == 1){
            return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'This account is not registered!']
        ]], 400);
        }
       
        $count = DB::table('password_resets')->where('email', $customer->email)->where('created_at', '>=', \Carbon\Carbon::now()->subHour())->count();
        if($count > 20){
          return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'You made a lot of requests! Try again later.']
        ]], 400);
        }
        
        $count = DB::table('password_resets')->where('email', $customer->email)->where('created_at', '>=', Carbon::today())->count();

        if($count > 5){
            return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'You made a lot of requests! Try again later.']
        ]], 400);

        }

        if (isset($customer)) {
            $token = rand(1000,9999);
            DB::table('password_resets')->insert([
                'email' => $customer['email'],
                'token' => $token,
                'created_at' => now(),
            ]);
            Mail::to($customer['email'])->send(new \App\Mail\PasswordResetMail($token));
            return response()->json(['message' => 'Email sent successfully.'], 200);
        }
        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => 'Email not found!']
        ]], 404);
    }

    public function verify_token(Request $request)
    {
        $email_verification = EmailVerifications::where(['token' => $request['reset_token'], 'email' => $request['email']])->first();

        if ($email_verification) {
            $createdAt = Carbon::parse($email_verification->created_at);
            $now = Carbon::now();
            $diffInMinutes = $now->diffInMinutes($createdAt);

            if ($diffInMinutes > 10) {
               return response()->json(['errors' => [
                 ['code' => 'invalid', 'message' => 'Code expired.']
              ]], 400);
            } else {
            $data = DB::table('email_verifications')->where(['token' => $request['reset_token'],'email'=>$request['email']])->delete();
             return response()->json(['message'=>"Token found, you can proceed"], 200);
            }
        } else {
          return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid token.']
        ]], 400);
        }
    }
    
     public function verify_password_token(Request $request)
    {
        $email_verification =   DB::table('password_resets')->where(['token' => $request['reset_token'], 'email' => $request['email']])->first();
        
        if ($email_verification) {
            $createdAt = Carbon::parse($email_verification->created_at);
            $now = Carbon::now();
            $diffInMinutes = $now->diffInMinutes($createdAt);

            if ($diffInMinutes > 10) {
               return response()->json(['errors' => [
                 ['code' => 'invalid', 'message' => 'Code expired.']
              ]], 400);
            } else {
             $data = DB::table('password_resets')->where(['token' => $request['reset_token'], 'email' => $request['email']])->delete();
             return response()->json(['message'=>"Token found, you can proceed"], 200);
            }
        } else {
          return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid token.']
        ]], 400);
        }
    }

    public function reset_password_submit(Request $request)
    {
        
       if($request['password'] == $request['confirm_password']) {
                DB::table('users')->where(['email' => $request->email])->update([
                    'password' => bcrypt($request['confirm_password'])
                ]);
                return response()->json(['message' => 'Password changed successfully.'], 200);
            }
            return response()->json(['errors' => [
                ['code' => 'mismatch', 'message' => 'Password did not match!']
            ]], 401);
       
        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid token.']
        ]], 400);
    }
}
