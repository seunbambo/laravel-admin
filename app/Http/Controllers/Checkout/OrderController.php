<?php

namespace App\Http\Controllers\Checkout;

use App\Link;
use App\Order;
use App\OrderItem;
use App\Product;
use DB;
use Illuminate\Http\Request;

class OrderController
{
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'code' => 'required|string',
            // 'user_id' => 'required|integer',
            // 'influencer_email' => 'required|email',
            // 'address' => 'required|string',
            // 'address2' => 'required|string',
            // 'city' => 'required|string',
            // 'country' => 'required|string',
            // 'zip' => 'required|integer',
            // 'price' => 'required|integer',
            // 'product_id' => 'required|integer',
            // 'order_id' => 'required|integer',
            // 'quantity' => 'required|integer',
            // 'zip' => 'required|integer',
            // 'influencer_revenue' => 'required|integer',
            // 'admin_revenue' => 'required|integer',
        ]);

        $link = Link::whereCode($request->input('code'))->first();

        dd($link);

        DB::beginTransaction();

        $order = new Order();

        $order->first_name = $request->input('first_name');
        $order->last_name = $request->input('last_name');
        $order->email = $request->input('email');
        $order->code = $link->code;
        $order->user_id = $link->id;
        $order->influencer_email = $link->email;
        $order->address = $request->input('address');
        $order->address2 = $request->input('address2');
        $order->city = $request->input('city');
        $order->country = $request->input('country');
        $order->zip = $request->input('zip');

        $order->save();

        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_title = $product->title;
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];
            $orderItem->influencer_revenue = 0.1 * $product->price * $item['quantity'];
            $orderItem->admin_revenue = 0.9 * $product->price * $item['quantity'];

            $orderItem->save();
        }

        DB::commit();

        return $order;
    }
}
