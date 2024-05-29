<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Model\DMReview;
use App\Model\Earning;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EarningsController extends Controller
{

    public function view($id)
    {
        $earning = Earning::with('driver')->where('id', $id)->first();
        $order = Order::where('id', $earning->order_id)->whereHas('order_routes', function ($query) use ($id) {
            $query->where('driver_id', $id);
        })->first();
        return view('admin-views.earnings.view', compact('earning', 'order'));
    }

    public function list()
    {
        $earnings = Earning::with('driver')->latest()->paginate(10);
        return view('admin-views.earnings.list', compact('earnings'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $earnings=Earning::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view'=>view('admin-views.earnings.partials._table',compact('earnings'))->render()
        ]);
    }

    public function status(Request $request)
    {
        $earning = Earning::find($request->id);
        $earning->status = $request->status;
        $earning->save();
        Toastr::success('Earning status updated!');
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
