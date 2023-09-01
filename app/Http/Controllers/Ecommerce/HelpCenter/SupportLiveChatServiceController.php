<?php

namespace App\Http\Controllers\Ecommerce\HelpCenter;

use App\Http\Controllers\Controller;
use App\Models\AgentStatus;
use App\Models\LiveChat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class SupportLiveChatServiceController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $liveChat = LiveChat::with("agent:id,name,avatar")->select("id", "user_id", "agent_id")->where("user_id", auth()->id())->first();

        return inertia("Ecommerce/HelpCenter/LiveChat/Index", compact("liveChat"));
    }

    public function store(Request $request): RedirectResponse
    {

        $liveChat = LiveChat::firstOrCreate(["user_id" => $request->user_id,"agent_id" => $request->agent_id], ["user_id" => $request->user_id,"agent_id" => $request->agent_id]);

        $availableAgent = AgentStatus::where("online_status", "online")
        ->where("chat_status", "avaliable")
        ->whereColumn('current_chat_count', '<', 'max_chat_capacity')
        ->first();

        if ($availableAgent) {
            $liveChat->update(["agent_id" => $availableAgent->id,"is_active" => true]);
        }

        return to_route("service.live-chat.index");
    }
}
