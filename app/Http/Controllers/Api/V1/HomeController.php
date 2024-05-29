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
use App\Model\Sightseeing;
use App\Model\Trip;
use App\Model\Service;
use App\Model\Category;

class HomeController extends Controller
{
    public function featured_activities(Request $request)
    {
       $trips = Trip::active()
       ->with('highlights', 'availability', 'options')
       ->where('featured', 1)
       ->withCount(['reviews as reviews_count', 'reviews as avg_rating' => function ($query) {
        $query->select(DB::raw('coalesce(avg(rating), 0)'));
       }])
       ->get();


        $sightseeings = Sightseeing::active()
        ->withCount(['reviews as reviews_count', 'reviews as avg_rating' => function ($query) {
        $query->select(DB::raw('coalesce(avg(rating), 0)'));
       }])
        ->where('featured' , 1)->get();
        
        
        $restaurants = Service::active()
        ->with(['sub_parent.parent' => function ($query) {
        $query->where('parent_id', 6); // Parent id 6 means food & beverage
        }])
        ->where(['featured' => 1])
        ->get();
        
        $categories = Category::active()->with('translations')->where('position', 0)->get();
        
        $featured = [
            'trips' => $trips,
            'sightseeings' => $sightseeings,
            'restaurants' => $restaurants,
            'categories' => $categories,
            ];
        return $featured;
    }
}