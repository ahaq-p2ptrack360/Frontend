@extends('layouts.master')

@section('title', 'AI Agent')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/agent-chat.css') }}">
    <style>
        .main-content:has(.agent-chat-page) .page-content {
            height: calc(100vh - 70px) !important;
            overflow: hidden !important;
        }

        .main-content:has(.agent-chat-page) .container-fluid {
            height: 100% !important;
            padding: 0 !important;
        }

        .agent-chat-page {
            position: relative !important;
            top: auto !important;
            height: calc(100vh - 100px) !important;
            min-height: 0 !important;
            padding: 16px !important;
            overflow: hidden !important;
        }

        .agent-chat-shell {
            position: relative !important;
            top: auto !important;
            height: 100% !important;
            min-height: 0 !important;
        }

        .agent-chat-main,
        .agent-chat-messages {
            min-height: 0;
        }

        .agent-chat-header {
            position: relative !important;
            top: auto !important;
            z-index: 1 !important;
        }

        .agent-chat-chart {
            height: 240px;
            margin-top: 14px;
            padding: 10px;
            border: 1px solid var(--agent-border);
            border-radius: 4px;
            background: #fff;
        }

        @media (max-width: 991px) {
            .agent-chat-page {
                height: calc(100vh - 70px);
                padding: 0;
            }
        }
    </style>
@endsection

@section('content')
<div class="agent-chat-page" data-company-id="{{ Auth::user()->company_id ?? 1 }}" data-user-id="{{ Auth::id() ?? 1 }}">
    <div class="agent-chat-shell">
        <aside class="agent-chat-sidebar" id="agentChatSidebar">
            <div class="agent-chat-sidebar__brand">
                <span class="agent-chat-brand-mark"><i class="ri-sparkling-2-line"></i></span>
                <div><span class="agent-chat-kicker">Workspace</span><strong>AI Agent</strong></div>
                <button class="agent-chat-icon-button agent-chat-sidebar-close d-lg-none" type="button" aria-label="Close menu"><i class="ri-close-line"></i></button>
            </div>
            <button class="agent-chat-new-button" type="button" id="newChatButton"><i class="ri-add-line"></i> New conversation</button>
            <div class="agent-chat-history">
                <span class="agent-chat-label">Recent chats</span>
                <button class="agent-chat-history-item is-active" type="button"><i class="ri-message-3-line"></i><span>Getting started with AI</span><small>Now</small></button>
                <button class="agent-chat-history-item" type="button"><i class="ri-message-3-line"></i><span>Weekly report summary</span><small>Yesterday</small></button>
                <button class="agent-chat-history-item" type="button"><i class="ri-message-3-line"></i><span>Draft a customer reply</span><small>Mon</small></button>
            </div>
            <div class="agent-chat-sidebar__footer">
                <div class="agent-chat-ready"><i class="ri-checkbox-circle-fill"></i> Agent ready</div>
                <a href="{{ url('/dashboard') }}"><i class="ri-arrow-left-line"></i> Back to dashboard</a>
            </div>
        </aside>

        <section class="agent-chat-main">
            <header class="agent-chat-header">
                <button class="agent-chat-icon-button d-lg-none" type="button" id="agentChatMenuButton" aria-label="Open menu"><i class="ri-menu-line"></i></button>
                <div class="agent-chat-title">
                    <span class="agent-chat-avatar"><i class="ri-sparkling-2-line"></i></span>
                    <div><h1>AI Assistant</h1><span><i class="ri-checkbox-circle-fill"></i> Online</span></div>
                </div>
                <div class="agent-chat-header-actions">
                    <button class="agent-chat-icon-button" type="button" id="clearChatButton" aria-label="Clear chat" title="Clear chat"><i class="ri-delete-bin-6-line"></i></button>
                    <button class="agent-chat-icon-button" type="button" aria-label="More options" title="More options"><i class="ri-more-2-fill"></i></button>
                </div>
            </header>

            <div class="agent-chat-messages" id="agentChatMessages" aria-live="polite">
                <div class="agent-chat-welcome" id="agentChatWelcome">
                    <span class="agent-chat-welcome-icon"><i class="ri-sparkling-2-line"></i></span>
                    <h2>How can I help today?</h2>
                    <p>Ask me to summarize, draft, organize, or explore an idea.</p>
                    <div class="agent-chat-prompts">
                        <button type="button" data-prompt="Summarize the latest updates for me">Summarize latest updates <i class="ri-arrow-right-line"></i></button>
                        <button type="button" data-prompt="Help me draft a professional customer reply">Draft a customer reply <i class="ri-arrow-right-line"></i></button>
                        <button type="button" data-prompt="Turn these notes into a clear action plan">Create an action plan <i class="ri-arrow-right-line"></i></button>
                        <button type="button" data-prompt="Explain how I can use this AI assistant">What can you do? <i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
            </div>

            <footer class="agent-chat-composer-wrap">
                <form class="agent-chat-composer" id="agentChatComposer">
                    <button class="agent-chat-attach" type="button" aria-label="Attach file" title="Attach file"><i class="ri-attachment-2"></i></button>
                    <textarea id="agentChatInput" rows="1" placeholder="Message your AI assistant..." aria-label="Message your AI assistant"></textarea>
                    <button class="agent-chat-send" type="submit" aria-label="Send message" title="Send message"><i class="ri-arrow-up-line"></i></button>
                </form>
                <p><i class="ri-information-line"></i> AI responses are generated from your workspace data.</p>
            </footer>
        </section>

        <aside class="agent-chat-details d-xl-block d-none">
            <div class="agent-chat-details__heading"><span class="agent-chat-label">Agent details</span><button class="agent-chat-icon-button" type="button" aria-label="Close details"><i class="ri-close-line"></i></button></div>
            <div class="agent-chat-profile"><span class="agent-chat-avatar agent-chat-avatar--large"><i class="ri-sparkling-2-line"></i></span><h2>AI Assistant</h2><span><i class="ri-checkbox-circle-fill"></i> Available</span></div>
            <div class="agent-chat-capabilities"><span class="agent-chat-label">Capabilities</span><div><i class="ri-file-text-line"></i> Summarize information</div><div><i class="ri-pencil-line"></i> Write and refine drafts</div><div><i class="ri-list-check-2"></i> Organize your next steps</div></div>
            <div class="agent-chat-private-note"><i class="ri-shield-check-line"></i><span>Your workspace stays private.</span></div>
        </aside>
    </div>
</div>

<div class="modal fade" id="agentChatDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this conversation? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="agentChatDeleteConfirmBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>window.agentApiBase = @json(url('/api'));</script>
    <script src="{{ asset('js/agent-chat.js') }}"></script>
@endsection
