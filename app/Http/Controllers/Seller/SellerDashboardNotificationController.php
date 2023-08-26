<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\ReadNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SellerDashboardNotificationController extends Controller
{
    public function reatNotification(string $notificationId): RedirectResponse
    {
        (new ReadNotificationService())->read($notificationId);

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->validate([
            "notifications" => ["required", "array"]
        ]);

        (new ReadNotificationService())->readAll($request->notifications);

        return back();
    }
}
