<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index(Request $request){

        $orders = Order::query();

        $status = $request->query("status");
        $sort = $request->query("sort");
        $order_id = $request->query("order_id");

        if($status != "" && in_array($status, ["pending", "processing", "completed", "cancelled"])){
            $orders->where("status", $status);
        }

        if($sort != "" && in_array($sort, ["created_at_desc", "created_at_asc"])){
            switch($sort){
                case "created_at_desc":
                    $orders->orderBy("created_at", "desc");
                    break;
                case "created_at_asc":
                    $orders->orderBy("created_at", "asc");
                    break;
            }
        }

        if($order_id != ""){
            $orders->where("id", $order_id);
        }

        $orders = $orders->simplePaginate(10);

        return response()->json([
            "data" => $orders
        ]);
    }
}
