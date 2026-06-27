@extends('layouts.app')
@section('content')

<style>
    :root {
        --chat-bg: #f8fafc;
        --chat-primary: #0f172a;
        --chat-accent: #3b82f6;
        --chat-saffron: #f97316;
        --chat-border: #e2e8f0;
    }
    
    .chat-container {
        display: flex;
        height: 75vh;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        border: 1px solid var(--chat-border);
        overflow: hidden;
    }

    .chat-sidebar {
        width: 250px;
        background: #f1f5f9;
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 20px;
        background: var(--chat-primary);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-link {
        display: block;
        padding: 15px 20px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px solid var(--chat-border);
        transition: 0.2s;
    }

    .section-link:hover {
        background: #e2e8f0;
        color: var(--chat-primary);
    }

    .section-link.active {
        background: white;
        color: var(--chat-saffron);
        border-left: 4px solid var(--chat-saffron);
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: white;
    }

    .chat-header {
        padding: 20px;
        border-bottom: 1px solid var(--chat-border);
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--chat-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .message-box {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 12px;
        position: relative;
    }

    .msg-incoming {
        background: white;
        border: 1px solid var(--chat-border);
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }

    .msg-outgoing {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }

    .msg-header {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 5px;
    }

    .msg-name {
        font-weight: 700;
        color: var(--chat-primary);
        font-size: 0.85rem;
    }

    .msg-role {
        font-size: 0.7rem;
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        color: #475569;
        font-weight: 600;
    }

    .msg-outgoing .msg-role {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .msg-role.role-admin {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .msg-role.role-dean {
        background: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #d8b4fe;
    }

    .msg-text {
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0;
    }

    .msg-time {
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: right;
        margin-top: 5px;
    }

    .chat-input-area {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid var(--chat-border);
    }

</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--chat-primary);">
                <i class="fas fa-comments text-primary me-2"></i> Communications Center
            </h2>
            <p class="text-muted m-0">Secure communication hub for Class Representatives (CR), Coordinators (CC), and Administration.</p>
        </div>
        <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-layer-group text-warning"></i> Channels
            </div>
            <div class="d-flex flex-column h-100">
                @foreach($validSections as $sec)
                    <a href="?section={{ $sec }}" class="section-link {{ $section == $sec ? 'active' : '' }}">
                        @if($sec == 'General') <i class="fas fa-hashtag me-2"></i>
                        @elseif($sec == 'Academics') <i class="fas fa-book me-2"></i>
                        @elseif($sec == 'Exams') <i class="fas fa-pen me-2"></i>
                        @elseif($sec == 'Placements') <i class="fas fa-briefcase me-2"></i>
                        @elseif($sec == 'Administration') <i class="fas fa-building me-2"></i>
                        @endif
                        {{ $sec }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-header">
                <span style="color: var(--chat-saffron);">#</span> {{ $section }}
            </div>
            
            <div class="chat-messages" id="chatWindow">
                @if(count($messages) == 0)
                    <div class="text-center text-muted mt-5">
                        <i class="fas fa-comment-dots fa-3x mb-3" style="opacity: 0.2;"></i>
                        <h5>No messages in {{ $section }}</h5>
                        <p class="small">Start the conversation by sending a message below.</p>
                    </div>
                @endif

                @foreach($messages as $msg)
                    @php
                        // Determine if message is from the current user based on name
                        $isOutgoing = ($msg->sender_name == session('staff_name') || (session('user_role') == 'admin' && $msg->sender_name == 'Admin'));
                    @endphp
                    <div class="message-box {{ $isOutgoing ? 'msg-outgoing' : 'msg-incoming' }}">
                        <div class="msg-header">
                            <span class="msg-name">{{ $msg->sender_name }}</span>
                            <span class="msg-role role-{{ strtolower($msg->sender_role) }}">{{ strtoupper($msg->sender_role) }}</span>
                        </div>
                        <p class="msg-text">{{ $msg->message }}</p>
                        <div class="msg-time">{{ $msg->created_at->format('d M y, h:i A') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="chat-input-area">
                <form method="POST" action="/admin/chat/send" class="d-flex gap-2">
                    @csrf
                    <input type="hidden" name="section" value="{{ $section }}">
                    <input type="text" name="message" class="form-control" placeholder="Type your message in #{{ $section }}..." required autocomplete="off">
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom of chat window
    window.onload = function() {
        var chatWindow = document.getElementById("chatWindow");
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
</script>

@endsection
