<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Member;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['categories'] = Category::get();
        $data['member'] = Member::get();
        return view('order.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // validate incoming request
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'order_payload' => 'required|string',
        ]);

        $payload = json_decode($validated['order_payload'], true);
        if (!$payload || empty($payload['items'])) {
            return redirect()->back()->with('error', 'No Items In Order');
        }

        DB::beginTransaction();
        try {
            // create order
            $order = new Order();
            $order->invoice = 'INV' . time();
            $order->total = $payload['total'] ?? array_sum(array_column($payload['items'], 'price'));
            // store authenticated user id or null when not logged in
            $order->user_id = Auth::id();
            $order->member_id = $validated['member_id'];
            $order->save();

            // create order details
            foreach ($payload['items'] as $item) {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->product_id = $item['id'];
                $detail->quantity = $item['qty'];
                // store per-item total (qty * unitPrice)
                $detail->price = $item['price'];
                $detail->save();
            }

            DB::commit();
            // redierect to print invoice page after success save

            return response()->json([
                'success' => true,
                'print_url' => route('order.print', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Show printable invoice for the order and trigger print
     */
    public function print(Order $order)
    {
        // load order details and customer
        $details = \App\Models\OrderDetail::where('order_id', $order->id)->get();
        // collect product ids and load products map
        $productIds = $details->pluck('product_id')->unique()->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        return view('order.print', [
            'order' => $order,
            'details' => $details,
            'products' => $products,
        ]);
    }
}
