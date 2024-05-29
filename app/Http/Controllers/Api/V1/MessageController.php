<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Message;
use Carbon\Carbon;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\User;


class MessageController extends Controller
{
    public function messages(Request $request)
    {
        $messages = Message::latest()->paginate('50', ['*'], 'page', $request['offset']);  
        
        $messages = [
            'total_size' => $messages->total(),
            'limit' => 50,
            'offset' => $request['offset'],
            'messages' => $messages->items()
        ];

        return $messages;
    }
}