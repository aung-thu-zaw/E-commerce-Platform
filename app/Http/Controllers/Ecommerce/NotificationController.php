<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReadNotificationService;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
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
