<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chat with {{ $userName }} - {{ config('app.name', 'Bookshop') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <style>
        .reply-highlight > div {
            border-left: 3px solid #10b981 !important;
            border-right: 3px solid #10b981 !important;
        }
        </style>
    </head>
    <body class="bg-gray-50 font-sans">
        <x-flash-message />
        
        <x-admin-navbar />

        <main class="max-w-4xl mx-auto px-4 py-6">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.chat.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Chat</h1>
                    </div>
                </div>
                <form action="{{ route('admin.chat.conversation.delete', $uniqueId) }}" method="POST" onsubmit="return confirm('Delete conversation?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">
                        Delete
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 h-[450px] flex flex-col">
                <div class="flex-1 overflow-y-auto p-3 space-y-3" id="chat-messages">
                            @forelse($chats as $chat)
                                @php
                                    $chatsArray = $chats->toArray();
                                    $repliedChat = null;
                                    $replyPreview = '';
                                    if ($chat->replied_message_id) {
                                        foreach ($chatsArray as $c) {
                                            if ($c['id'] == $chat->replied_message_id) {
                                                $repliedChat = $c;
                                                break;
                                            }
                                        }
                                        if ($repliedChat) {
                                            $replyPreview = mb_substr($repliedChat['message'], 0, 60);
                                            if (mb_strlen($repliedChat['message']) > 60) $replyPreview .= '...';
                                        }
                                    }
                                    $currentPreview = mb_substr($chat->message, 0, 50);
                                    if (mb_strlen($chat->message) > 50) $currentPreview .= '...';
                                @endphp
                                <div class="flex {{ $chat->sender_type === 'admin' ? 'justify-start' : 'justify-end' }} group {{ $repliedChat ? 'reply-highlight' : '' }}" data-chat-id="{{ $chat->id }}" ondblclick="selectReply({{ $chat->id }}, '{{ addslashes($currentPreview) }}')" on touchstart="handleDoubleTap(event, {{ $chat->id }}, '{{ addslashes($currentPreview) }}')">
                                    <div class="max-w-[80%] {{ $chat->sender_type === 'admin' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-2xl px-4 py-3 relative cursor-pointer">
                                        @if($repliedChat)
                                        <div class="text-xs mb-2 px-2 py-1 rounded {{ $chat->sender_type === 'admin' ? 'bg-indigo-500 text-indigo-200' : 'bg-gray-200 text-gray-600' }}">
                                            ↩ {{ $replyPreview }}
                                        </div>
                                        @endif
                                        <button onclick="selectReply({{ $chat->id }}, '{{ addslashes($currentPreview) }}')" class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" title="Reply">↩</button>
                                        <button onclick="deleteMessage({{ $chat->id }})" class="absolute -top-2 -right-8 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" title="Delete">×</button>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-semibold {{ $chat->sender_type === 'admin' ? 'text-indigo-200' : 'text-gray-500' }}">
                                                {{ $chat->sender_type === 'admin' ? 'Admin' : ($chat->name ?? 'Guest') }}
                                            </span>
                                            @if($chat->sender_type === 'customer' && !$chat->is_read)
                                                <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded">New</span>
                                            @endif
                                        </div>
                                        <p class="text-sm">{{ $chat->message }}</p>
                                        <div class="flex items-center justify-end gap-1 mt-1">
                                            <p class="text-xs {{ $chat->sender_type === 'admin' ? 'text-indigo-200' : 'text-gray-400' }}">{{ $chat->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-gray-500 py-8">No messages yet</div>
                            @endforelse
                        </div>

                        <div class="border-t border-gray-100 p-4">
                            <div id="reply-to-container" class="hidden mb-2 p-2 bg-gray-100 rounded-lg flex items-center justify-between">
                                <span class="text-sm text-gray-600">Replying to: <span id="reply-to-preview" class="font-medium"></span></span>
                                <button type="button" onclick="clearReply()" class="text-gray-500 hover:text-gray-700">×</button>
                            </div>
                            <form action="{{ route('admin.chat.reply', $uniqueId ?? $ipAddress) }}" method="POST" class="flex gap-3">
                                @csrf
                                <input type="hidden" name="replied_message_id" id="replied_message_id" value="">
                                <input type="text" name="message" placeholder="Type your reply..." class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required maxlength="5000">
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                    Send
                                </button>
                            </form>
                        </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mt-4">
                <div class="text-xs text-gray-500">
                    <span class="font-medium">IP:</span> {{ $ipAddress }} | 
                    <span class="font-medium">Messages:</span> {{ $chats->count() }}
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-6">
            <div class="max-w-4xl mx-auto px-4 py-4">
                <p class="text-center text-xs text-gray-500">&copy; {{ date('Y') }} Bookshop Admin</p>
            </div>
        </footer>

        <script>
        let lastTapTime = 0;
        let lastTapElement = null;

        function handleDoubleTap(event, id, preview) {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTapTime;
            
            if (tapLength < 300 && tapLength > 0 && lastTapElement === event.currentTarget) {
                selectReply(id, preview);
                event.preventDefault();
            }
            lastTapTime = currentTime;
            lastTapElement = event.currentTarget;
        }

        function deleteMessage(id) {
            if (!confirm('Delete this message?')) return;
            
            fetch('{{ route("admin.chat.message.delete", ["id" => ":id"]) }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(err => console.error('Error:', err));
        }

        let selectedReplyId = null;

        function selectReply(id, preview) {
            selectedReplyId = id;
            document.getElementById('replied_message_id').value = id;
            document.getElementById('reply-to-preview').textContent = preview;
            document.getElementById('reply-to-container').classList.remove('hidden');
            
            document.querySelectorAll('.reply-highlight').forEach(el => el.classList.remove('reply-highlight'));
            const chatDiv = document.querySelector(`[data-chat-id="${id}"]`);
            if (chatDiv) {
                chatDiv.classList.add('reply-highlight');
                chatDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function clearReply() {
            selectedReplyId = null;
            document.getElementById('replied_message_id').value = '';
            document.getElementById('reply-to-container').classList.add('hidden');
            document.querySelectorAll('.reply-highlight').forEach(el => el.classList.remove('reply-highlight'));
        }
        </script>
    </body>
</html>