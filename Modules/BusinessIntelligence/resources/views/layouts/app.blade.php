<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nexora — Business Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bi/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <header class="header">
        <a href="{{ route('bi.dashboard') }}" class="nexora-logo">
            <img src="{{ asset('bi/images/Banner Transparent.png') }}" alt="Nexora">
        </a>
        <div class="header-right">
            <span style="font-size:12px;color:var(--slate-500)">{{ session('employee_name', auth()->user()?->username ?? 'Nexora user') }}</span>
            <div class="header-profile-wrap" id="headerProfileWrap">
                <button class="header-profile-btn" id="headerProfileBtn" type="button" aria-label="View BI notifications">
                    <i data-lucide="bell" class="profile-icon"></i>
                    <span class="notification-badge" id="notificationBadge">0</span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-header"><h3>Client alerts</h3><button class="notification-mark-read" id="markRead" type="button">Mark all read</button></div>
                    <div class="notification-list" id="notificationList"><p style="text-align:center;color:var(--slate-500);padding:2rem;font-size:11px">Loading alerts…</p></div>
                </div>
            </div>
        </div>
    </header>
    <div class="app-body">
        <aside>
            <div class="nav-menu">
                <a href="{{ route('bi.dashboard') }}" class="nav-item {{ request()->routeIs('bi.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <div class="nav-item-title"><i data-lucide="layout-dashboard" class="nav-icon"></i>Dashboard</div>
                    <div class="nav-item-sub">Executive overview</div>
                </a>
                <a href="{{ route('bi.department-analytics') }}" class="nav-item {{ request()->routeIs('bi.department-analytics') ? 'active' : '' }}" data-tooltip="Department Analytics">
                    <div class="nav-item-title"><i data-lucide="building-2" class="nav-icon"></i>Department Analytics</div>
                    <div class="nav-item-sub">KPI deep dive</div>
                </a>
                <a href="{{ route('bi.live-monitor') }}" class="nav-item {{ request()->routeIs('bi.live-monitor') ? 'active' : '' }}" data-tooltip="Live Monitor">
                    <div class="nav-item-title"><i data-lucide="activity" class="nav-icon"></i>Live Monitor</div>
                    <div class="nav-item-sub">Client activity feed</div>
                </a>
                <a href="{{ route('bi.ai-insights') }}" class="nav-item {{ request()->routeIs('bi.ai-insights') ? 'active' : '' }}" data-tooltip="AI Insights">
                    <div class="nav-item-title"><i data-lucide="brain" class="nav-icon"></i>AI Insights</div>
                    <div class="nav-item-sub">Coming next</div>
                </a>
            </div>
            <div class="sidebar-footer"><i data-lucide="shield-check" class="footer-icon"></i><span>Client-scoped BI</span></div>
        </aside>
        <main>@yield('content')</main>
    </div>
    <div class="ai-chat-bot" id="aiChatBot">
        <button class="ai-chat-toggle" id="aiChatToggle" type="button" title="Nexora AI Business Analyst">
            <img src="{{ asset('bi/images/Nexora_Logo_Transparent.png') }}" class="chat-toggle-logo" alt="Open Nexora AI">
        </button>
        <div class="ai-chat-window" id="aiChatWindow">
            <div class="ai-chat-header"><div class="ai-chat-header-left"><img src="{{ asset('bi/images/Nexora_Logo_Transparent.png') }}" class="chat-header-logo" alt="Nexora"><div><h4>Nexora AI Business Analyst</h4><p>Client-scoped BI insights</p></div></div><button class="ai-chat-close" id="aiChatClose" type="button" aria-label="Close AI chat"><i data-lucide="x" class="chat-close-icon"></i></button></div>
            <div class="ai-chat-messages" id="aiChatMessages"><div class="ai-message ai-message-bot"><div class="ai-message-avatar"><img src="{{ asset('bi/images/Nexora_Logo_Transparent.png') }}" class="msg-avatar-logo" alt="Nexora"></div><div class="ai-message-content">Ask about revenue, expenses, inventory alerts, orders, fulfillment, or manufacturing activity.</div></div></div>
            <div class="ai-chat-input-container"><div class="ai-suggestion-chips"><button class="ai-chip" type="button" data-question="Give me a concise summary of our current business performance.">Business summary</button><button class="ai-chip" type="button" data-question="What operational risks need attention right now?">Risk alerts</button><button class="ai-chip" type="button" data-question="What should the team focus on this week?">Weekly priorities</button></div><div class="ai-chat-input-row"><input class="ai-chat-input" id="aiChatInput" maxlength="1500" placeholder="Ask about your business…"><button class="ai-chat-send" id="aiChatSend" type="button" aria-label="Send AI question"><i data-lucide="send" class="send-icon"></i></button></div></div>
        </div>
    </div>
    <script>
    const biClientScope = @json(request()->integer('client_id') ?: null);
    const biLiveFeedUrl = @json(route('bi.live-feed'));
    const biChatUrl = @json(route('bi.ai.chat'));
    const biScopedUrl = (url) => url + (biClientScope ? (url.includes('?') ? '&' : '?') + 'client_id=' + biClientScope : '');
    const biEscape = (text) => { const element = document.createElement('div'); element.textContent = text; return element.innerHTML; };
    const biChatMessages = document.getElementById('aiChatMessages');
    const biAddMessage = (role, text) => { const item = document.createElement('div'); item.className = 'ai-message ai-message-' + role; item.innerHTML = role === 'bot' ? '<div class="ai-message-avatar"><img src="{{ asset('bi/images/Nexora_Logo_Transparent.png') }}" class="msg-avatar-logo" alt="Nexora"></div><div class="ai-message-content">' + biEscape(text) + '</div>' : '<div class="ai-message-content">' + biEscape(text) + '</div>'; biChatMessages.appendChild(item); biChatMessages.scrollTop = biChatMessages.scrollHeight; return item; };
    async function biAskAi(preset) { const input = document.getElementById('aiChatInput'); const message = preset || input.value.trim(); if (!message) return; biAddMessage('user', message); input.value = ''; const send = document.getElementById('aiChatSend'); send.disabled = true; const pending = biAddMessage('bot', 'Analyzing your client-scoped BI metrics…'); try { const response = await fetch(biScopedUrl(biChatUrl), {method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({message})}); const data = await response.json(); pending.remove(); biAddMessage('bot', data.message || 'AI Insights is temporarily unavailable.'); } catch (_) { pending.remove(); biAddMessage('bot', 'AI Insights is temporarily unavailable. Please try again shortly.'); } finally { send.disabled = false; } }
    const biReadAlerts = new Set(JSON.parse(localStorage.getItem('nexora-bi-read-alerts') || '[]'));
    function biRenderAlerts(alerts) { const list = document.getElementById('notificationList'); const unread = alerts.filter(alert => !biReadAlerts.has(alert.title)); document.getElementById('notificationBadge').textContent = unread.length || ''; document.getElementById('notificationBadge').style.display = unread.length ? 'flex' : 'none'; list.innerHTML = alerts.length ? alerts.slice(0, 6).map(alert => '<div class="notification-item ' + (biReadAlerts.has(alert.title) ? '' : 'unread') + '" data-alert="' + biEscape(alert.title) + '"><div class="notification-dot"></div><div class="notification-content"><p class="notification-title">' + biEscape(alert.title) + '</p><p class="notification-desc">' + biEscape(alert.description) + '</p></div></div>').join('') : '<p style="text-align:center;color:var(--slate-500);padding:2rem;font-size:11px">All clear for this client.</p>'; }
    async function biLoadAlerts() { try { const response = await fetch(biScopedUrl(biLiveFeedUrl)); const data = await response.json(); biRenderAlerts(data.alerts || []); } catch (_) { document.getElementById('notificationList').innerHTML = '<p style="text-align:center;color:var(--slate-500);padding:2rem;font-size:11px">Alerts are temporarily unavailable.</p>'; } }
    document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); document.getElementById('aiChatToggle').addEventListener('click', () => document.getElementById('aiChatBot').classList.toggle('ai-chat-open')); document.getElementById('aiChatClose').addEventListener('click', () => document.getElementById('aiChatBot').classList.remove('ai-chat-open')); document.getElementById('aiChatSend').addEventListener('click', () => biAskAi()); document.getElementById('aiChatInput').addEventListener('keydown', event => { if (event.key === 'Enter') biAskAi(); }); document.querySelectorAll('.ai-chip').forEach(button => button.addEventListener('click', () => biAskAi(button.dataset.question))); document.getElementById('headerProfileBtn').addEventListener('click', () => document.getElementById('notificationDropdown').classList.toggle('active')); document.getElementById('markRead').addEventListener('click', () => { document.querySelectorAll('.notification-item').forEach(item => biReadAlerts.add(item.dataset.alert)); localStorage.setItem('nexora-bi-read-alerts', JSON.stringify([...biReadAlerts])); biLoadAlerts(); }); biLoadAlerts(); setInterval(biLoadAlerts, 30000); });
    </script>
    @yield('scripts')
</body>
</html>
