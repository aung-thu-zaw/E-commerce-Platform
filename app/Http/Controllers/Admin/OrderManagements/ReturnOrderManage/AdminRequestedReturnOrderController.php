<?php

namespace App\Http\Controllers\Admin\OrderManagements\ReturnOrderManage;

use App\Http\Controllers\Controller;
use App\Models\DeliveryInformation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminRequestedReturnOrderController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $requestedReturnOrders=Order::search(request("search"))
                                  ->where("return_status", "requested")
                                  ->where("payment_type", "card")
                                  ->orderBy(request("sort", "id"), request("direction", "desc"))
                                  ->paginate(request("per_page", 10))
                                  ->appends(request()->all());

        return inertia("Admin/OrderManagements/ReturnOrderManage/RequestedReturnOrders/Index", compact("requestedReturnOrders"));
    }

    public function show(int $id): Response|ResponseFactory
    {
        $paginate=[ "page"=>request("page"),"per_page"=>request("per_page")];

        $requestedReturnOrderDetail=Order::findOrFail($id);

        $deliveryInformation=DeliveryInformation::where("user_id", $requestedReturnOrderDetail->user_id)->first();

        $orderItems=OrderItem::with("product.shop")->where("order_id", $requestedReturnOrderDetail->id)->get();

        return inertia("Admin/OrderManagements/ReturnOrderManage/RequestedReturnOrders/Detail", compact("paginate", "requestedReturnOrderDetail", "deliveryInformation", "orderItems"));
    }

    public function update(int $id): RedirectResponse
    {
        $order=Order::with(["deliveryInformation","orderItems.product.shop"])->findOrFail($id);

        $order->update([
            "return_status"=>"approved",
            "return_approved_date"=>now()->format("Y-m-d")
        ]);

        $order->orderItems()->each(function ($orderItem) {
            $orderItem->update([
                "return_status"=>"approved",
                "return_approved_date"=>now()->format("Y-m-d")
                ]);
        });

        // Mail::to($order->deliveryInformation->email)->send(new OrderDeliveredMail($order));

        return to_route("admin.return-orders.approved.index")->with("success", "Order return is approved.");
    }
}