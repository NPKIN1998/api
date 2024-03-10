<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = $request->user();
        $productId = $request->input('id');
        $product = Package::find($productId);

        if (!$product) {
            Log::alert($product);
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($user->balance >= $product->price) {
            // Subtract the balance
            $user->balance -= $product->price;
            $user->save();
            $n = Carbon::now();

            // Register the order
            $order = new Order([
                'user_id'=>$user->id,
                'price'=>$product->price,
                'daily_income'=>$product->daily_income,
                'profits'=>$product->daily_income * 30,
                'days_paid' => 0,
                'date_ordered' => $n,
                'days_to_earn' => 30,
            ]);
            Log::alert($order);

            $order->save();

            return response()->json(['message' => 'Order placed successfully']);
        } else {
            return response()->json(['error' => 'Insufficient balance']);
        }
    }

    public function allPackages(Request $request): \Illuminate\Http\JsonResponse
    {
        $p = Package::all();
        return response()->json(['packages' => $p]);
    }
}
