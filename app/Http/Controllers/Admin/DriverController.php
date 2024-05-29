<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Model\DMReview;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DriverController extends Controller
{

    public function view($id)
    {
        $driver = User::where('id', $id)->first();
        $orders = Order::whereHas('order_routes', function ($query) use ($id) {
            $query->where('driver_id', $id);
        })->paginate(10);
        return view('admin-views.driver.view', compact('driver', 'orders'));
    }

    public function list()
    {
        $delivery_men = User::latest()->paginate(10);
        return view('admin-views.driver.list', compact('delivery_men'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $delivery_men=User::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('full_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view'=>view('admin-views.driver.partials._table',compact('delivery_men'))->render()
        ]);
    }

    public function status(Request $request)
    {
        $delivery_man = User::find($request->id);
        $delivery_man->status = $request->status;
        $delivery_man->save();
        Toastr::success('Delivery-man status updated!');
        return back();
    }


    // public function delete(Request $request)
    // {
    //     $delivery_man = User::find($request->id);
    //     if (Storage::disk('public')->exists('delivery-man/' . $delivery_man['image'])) {
    //         Storage::disk('public')->delete('delivery-man/' . $delivery_man['image']);
    //     }

    //     foreach (json_decode($delivery_man['identity_image'], true) as $img) {
    //         if (Storage::disk('public')->exists('delivery-man/' . $img)) {
    //             Storage::disk('public')->delete('delivery-man/' . $img);
    //         }
    //     }

    //     $delivery_man->delete();
    //     Toastr::success('Delivery-man removed!');
    //     return back();
    // }
}
