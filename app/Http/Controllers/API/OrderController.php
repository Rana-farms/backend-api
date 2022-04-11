<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;


class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::get();
        $ordersResource = OrderResource::collection( $orders );
        return ApiResponse::successResponseWithData( $ordersResource, 'Orders retrieved', 200 );
    }


    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();
        $order = Order::create( $data );
        $orderResource = new OrderResource( $order );
        return ApiResponse::successResponseWithData( $orderResource, 'Order created', 203 );
    }


    public function show( $code )
    {
        $order = Order::where( 'code', $code )->first();
        if( $order ){
            $orderResource = new OrderResource( $order );
            return ApiResponse::successResponseWithData( $orderResource, 'Order retrieved', 200 );
        } else {
            return ApiResponse::errorResponse('Order not found', 404 );
        }

    }

    public function update(UpdateOrderRequest $request, $code)
    {
        $orderToUpdate = Order::where( 'code', $code )->first();
        $data = $request->validated();

        if( $orderToUpdate ){
            $orderToUpdate->update( $data );
            $orderResource = new OrderResource( $orderToUpdate );
            return ApiResponse::successResponseWithData( $orderResource, 'Order updated', 200 );
        } else{
            return ApiResponse::errorResponse('Order not found', 404 );
        }
    }

    public function destroy( $code )
    {
        $order = Order::where( 'code', $code )->first();
        if( $order ){
            $order->delete();
            return ApiResponse::successResponse('Order deleted', 200 );
        } else {
            return ApiResponse::errorResponse('Order not found', 404 );
        }
    }
}
