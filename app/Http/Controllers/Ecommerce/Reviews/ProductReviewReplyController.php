<?php

namespace App\Http\Controllers\Ecommerce\Reviews;

use App\Actions\Ecommerce\Reviews\CreateProductReviewReplyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductReviewReplyRequest;
use App\Models\Reply;
use App\Services\BroadcastNotifications\ProductReviewReplyFromSellerNotificationSendToUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewReplyController extends Controller
{
    public function store(ProductReviewReplyRequest $request): RedirectResponse
    {
        $reply = (new CreateProductReviewReplyAction())->handle($request->validated());

        (new ProductReviewReplyFromSellerNotificationSendToUserService())->send($reply);

        return back();
    }
}