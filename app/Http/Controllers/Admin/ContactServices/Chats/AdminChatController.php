<?php

namespace App\Http\Controllers\Admin\ContactServices\Chats;

use App\Http\Controllers\Controller;
use App\Models\ChatFolder;
use App\Models\LiveChat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminChatController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $liveChats = LiveChat::with([
            'user:id,name,avatar',
            'agent:id,name,avatar',
            'liveChatMessages.chatFileAttachments',
            'liveChatMessages.user:id,name,avatar',
            'liveChatMessages.agent:id,name,avatar',
            'liveChatMessages.replyToMessage',
        ])
                             ->filterBy(request(['search', 'tab']))
                             ->where('agent_id', auth()->id())
                             ->where('is_deleted_by_agent', false)
                             ->orderBy('pinned', 'desc')
                             ->get();

        $folders = ChatFolder::where('agent_id', auth()->id())->get();

        return inertia('Admin/ContactServices/Chats/Index', compact('liveChats', 'folders'));
    }

    public function show(LiveChat $liveChat): Response|ResponseFactory
    {
        $liveChats = LiveChat::with([
            'user:id,name,avatar',
            'agent:id,name,avatar',
            'liveChatMessages.chatFileAttachments',
            'liveChatMessages.user:id,name,avatar',
            'liveChatMessages.agent:id,name,avatar',
            'liveChatMessages.replyToMessage',
        ])
                             ->filterBy(request(['search', 'tab']))
                             ->where('agent_id', auth()->id())
                             ->where('is_deleted_by_agent', false)
                             ->orderBy('pinned', 'desc')
                             ->get();

        $liveChat->load([
            'user:id,name,avatar',
            'agent:id,name,avatar',
            'liveChatMessages.chatFileAttachments',
            'liveChatMessages.user:id,name,avatar',
            'liveChatMessages.agent:id,name,avatar',
            'liveChatMessages.replyToMessage',
        ]);

        $folders = ChatFolder::where('agent_id', auth()->id())->get();

        return inertia('Admin/ContactServices/Chats/Show', compact('liveChats', 'liveChat', 'folders'));
    }

    public function pinnedChat(Request $request, LiveChat $liveChat): RedirectResponse
    {
        $liveChat->update([
            'pinned' => $request->pinned,
        ]);

        return back();
    }

    public function deleteForMyself(Request $request, LiveChat $liveChat): RedirectResponse
    {
        $liveChat->update([
            'is_deleted_by_agent' => $request->is_deleted_by_agent,
        ]);

        return to_route('admin.live-chats.index', ['tab' => $request->tab]);
    }

    public function destroy(Request $request, LiveChat $liveChat): RedirectResponse
    {
        $liveChat->delete();

        return to_route('admin.live-chats.index', ['tab' => $request->tab]);
    }

    public function handleChatWithFolder(Request $request, LiveChat $liveChat): RedirectResponse
    {
        $liveChat->update([
            'chat_folder_id' => $request->chat_folder_id,
        ]);

        return back();
    }

    public function archivedChat(Request $request, LiveChat $liveChat): RedirectResponse
    {
        $liveChat->update([
            'archived' => $request->archived,
        ]);

        return back();
    }
}
