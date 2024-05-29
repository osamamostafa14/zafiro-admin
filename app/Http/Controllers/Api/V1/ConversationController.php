<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Conversation;
use Carbon\Carbon;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\User;

class ConversationController extends Controller
{
    public function messages_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (!empty($request->file('image'))) {
            $image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('conversation')) {
                Storage::disk('public')->makeDirectory('conversation');
            }
            $note_img = Image::make($request->file('image'))->stream();
            Storage::disk('public')->put('conversation/' . $image_name, $note_img);
        } else {
            $image_name = null;
        }

        $conv = new Conversation;
        $conv->user_id = $request->user()->id;
        $conv->message = $request->message;
        $conv->image = $image_name;
        $conv->save();

        return response()->json(['message' => 'successfully sent!'], 200);
    }
    

    public function chat_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (!empty($request->file('image'))) {
            $image_name = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('conversation')) {
                Storage::disk('public')->makeDirectory('conversation');
            }
            $note_img = Image::make($request->file('image'))->stream();
            Storage::disk('public')->put('conversation/' . $image_name, $note_img);
        } else {
            $image_name = 'def.png';
        }

        $url = asset('storage/app/public/conversation') . '/' . $image_name;

        return response()->json(['image_url' => $url], 200);
    }


     public function messages(Request $request)
    {   
         $conversations = Conversation::where(['user_id' => $request->user()->id])->latest()->take(25)->get();
        
        Conversation::where('user_id', $request->user()->id)->update(array(
                         'seen'=>1,
        )); 
        return response()->json([$conversations], 200);
    }
    
    public function messages_history(Request $request)
    {
        $conversations = Conversation::where(['user_id' => $request->user()->id])->latest()->paginate('50', ['*'], 'page', $request['offset']);  
        
        $conversations = [
            'total_size' => $conversations->total(),
            'limit' => 50,
            'offset' => $request['offset'],
            'conversations' => $conversations->items()
        ];

        return $conversations;
    }
  
  
    public function send_image(Request $request)
    {
        $image = $request->file('image');

        if ($request->hasFile('image')) {
            // return response()->json(['message' => 'image'], 200);
            $data = getimagesize($image);
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('conversation')) {
                Storage::disk('public')->makeDirectory('conversation');
            }
            $img = Image::make($image)->fit($data[0], $data[1])->stream();
            Storage::disk('public')->put('conversation/' . $imageName, $img);
        } else {
            return response()->json($request, 200);
            $imageName = 'No image';
        }
        
        $conversation = New Conversation();
        $conversation->user_id = $request->user()->id;
        $conversation->message = !empty($request->message) ? $request->message : '';
        $conversation->image = $imageName;
        $conversation->save();
 
        return response()->json(['message' => 'Message sent'], 200);
    }
}

