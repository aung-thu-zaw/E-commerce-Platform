<?php

namespace App\Http\Controllers\Admin\OrderManagements\OrderManage;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesQueryStringParameters;
use App\Jobs\Orders\SendDeliveredOrderEmailToCustomer;
use App\Models\DeliveryInformation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminShippedOrderController extends Controller
{
    use HandlesQueryStringParameters;

    public function index(): Response|ResponseFactory
    {
        $shippedOrders = Order::search(request('search'))
                              ->where('order_status', 'shipped')
                              ->where('cancel_status', null)
                              ->where('return_status', null)
                              ->where('return_status', null)
                              ->orderBy(request('sort', 'id'), request('direction', 'desc'))
                              ->paginate(request('per_page', 10))
                              ->appends(request()->all());

        return inertia('Admin/OrderManagements/OrderManage/ShippedOrders/Index', compact('shippedOrders'));
    }

    public function show(Request $request, Order $order): Response|ResponseFactory
    {
        $deliveryInformation = DeliveryInformation::where('user_id', $order->user_id)->first();

        $orderItems = OrderItem::with('product.shop')->where('order_id', $order->id)->get();

        $queryStringParams = $this->getQueryStringParams($request);

        return inertia('Admin/OrderManagements/OrderManage/ShippedOrders/Detail', compact('queryStringParams', 'order', 'deliveryInformation', 'orderItems'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->load(['orderItems.product.shop']);

        $order->update([
            'order_status' => 'delivered',
            'delivered_date' => now()->format('Y-m-d'),
        ]);

        SendDeliveredOrderEmailToCustomer::dispatch($order);

        return to_route('admin.orders.shipped.index', $this->getQueryStringParams($request))->with('success', 'Order is delivered');
    }
}
