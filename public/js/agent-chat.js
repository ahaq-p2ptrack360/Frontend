document.addEventListener('DOMContentLoaded', function () {
    const conversation = document.getElementById('agentChatMessages');
    const welcome = document.getElementById('agentChatWelcome');
    const composer = document.getElementById('agentChatComposer');
    const input = document.getElementById('agentChatInput');
    const sidebar = document.getElementById('agentChatSidebar');
    const menuButton = document.getElementById('agentChatMenuButton');
    const closeButton = document.querySelector('.agent-chat-sidebar-close');
    const newChatButton = document.getElementById('newChatButton');
    const clearButton = document.getElementById('clearChatButton');
    const agentPage = document.querySelector('.agent-chat-page');
    let conversationId = null;

    function addMessage(text, isUser, chart) {
        const message = document.createElement('div');
        message.className = 'agent-chat-message' + (isUser ? ' agent-chat-message--user' : '');
        message.innerHTML = isUser ? '<div class="agent-chat-message__body"></div>' : '<span class="agent-chat-message__avatar"><i class="ri-sparkling-2-line"></i></span><div class="agent-chat-message__body"></div>';
        message.querySelector('.agent-chat-message__body').textContent = text || '';
        conversation.appendChild(message);
        if (chart && !isUser) renderChart(message, chart);
        conversation.scrollTop = conversation.scrollHeight;
        return message;
    }

    function addTypingIndicator() {
        const message = document.createElement('div');
        message.className = 'agent-chat-message agent-chat-typing';
        message.innerHTML = '<span class="agent-chat-message__avatar"><i class="ri-sparkling-2-line"></i></span><div class="agent-chat-message__body"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
        conversation.appendChild(message);
        conversation.scrollTop = conversation.scrollHeight;
        return message;
    }

    function renderChart(message, chart) {
        const data = chart.data || chart;
        if (!data || !Array.isArray(data.labels) || !Array.isArray(data.datasets)) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'agent-chat-chart';
        const canvas = document.createElement('canvas');
        wrapper.appendChild(canvas);
        message.querySelector('.agent-chat-message__body').appendChild(wrapper);
        if (window.Chart) new Chart(canvas, { type: chart.type || 'bar', data: data, options: Object.assign({ responsive: true, maintainAspectRatio: false }, chart.options || {}) });
    }

    function renderSqlQuery(message, sql) {
        if (!sql || !sql.length) return;
        const details = document.createElement('details');
        details.className = 'agent-chat-query-plan';
        const summary = document.createElement('summary');
        summary.textContent = 'View database query';
        const pre = document.createElement('pre');
        pre.textContent = Array.isArray(sql) ? sql.join(';\n\n') + ';' : String(sql);
        details.appendChild(summary);
        details.appendChild(pre);
        message.querySelector('.agent-chat-message__body').appendChild(details);
    }

    function closeSidebar() { sidebar.classList.remove('is-open'); }

    function confirmDelete() {
        const modalEl = document.getElementById('agentChatDeleteModal');
        if (!modalEl || !window.bootstrap) return Promise.resolve(confirm('Delete this conversation?'));

        return new Promise(function (resolve) {
            const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
            const confirmBtn = document.getElementById('agentChatDeleteConfirmBtn');
            let resolved = false;

            function onConfirm() {
                resolved = true;
                modal.hide();
                resolve(true);
            }

            function onHidden() {
                confirmBtn.removeEventListener('click', onConfirm);
                modalEl.removeEventListener('hidden.bs.modal', onHidden);
                if (!resolved) resolve(false);
            }

            confirmBtn.addEventListener('click', onConfirm);
            modalEl.addEventListener('hidden.bs.modal', onHidden);
            modal.show();
        });
    }

    function clearMessages() {
        conversation.querySelectorAll('.agent-chat-message').forEach(function (message) { message.remove(); });
        welcome.hidden = false;
        conversationId = null;
        input.focus();
        closeSidebar();
    }

    async function deleteCurrentConversation() {
        if (!conversationId) { clearMessages(); return; }
        const confirmed = await confirmDelete();
        if (!confirmed) return;
        try {
            const response = await fetch(window.agentApiBase + '/ai/conversations/' + conversationId, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            if (!response.ok) return;
            document.querySelectorAll('.agent-chat-history-item').forEach(function (item) {
                if (item.classList.contains('is-active')) item.remove();
            });
            clearMessages();
        } catch (error) {}
    }

    async function sendMessage(text) {
        const value = (text || input.value).trim();
        if (!value) return;
        welcome.hidden = true;
        addMessage(value, true);
        input.value = '';
        input.style.height = 'auto';
        const sendButton = composer.querySelector('.agent-chat-send');
        sendButton.disabled = true;
        
        const typingIndicator = addTypingIndicator();
        
        try {
            const endpoint = window.agentApiBase + '/ai/query/plan';
            const body = {
                question: value,
                company_id: Number(agentPage?.dataset.companyId || 1),
                user_id: Number(agentPage?.dataset.userId || 1),
                conversation_id: conversationId
            };
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                body: JSON.stringify(body)
            });
            const payload = await response.json();
            if (!response.ok || payload.success === false) throw new Error(payload.message || payload.error || 'AI request failed.');
            conversationId = payload.conversation_id || conversationId;
            typingIndicator.remove();
            const assistantMessage = addMessage(payload.answer || payload.message || 'No answer returned.', false);
            renderDemoChart(assistantMessage, payload.chart || payload.charts);
            renderSqlQuery(assistantMessage, payload.sql);
            if (payload.plan || payload.result) updateDebug(payload);
            updateHistoryTitle(value);
        } catch (error) {
            typingIndicator.remove();
            addMessage(error.message || 'Unable to reach the AI service.', false);
        } finally {
            sendButton.disabled = false;
            input.focus();
        }
    }

    async function loadHistory() {
        try {
            const response = await fetch(window.agentApiBase + '/ai/conversations', { headers: { Accept: 'application/json' } });
            if (!response.ok) return;
            const conversations = await response.json();
            const history = document.querySelector('.agent-chat-history');
            history.querySelectorAll('.agent-chat-history-item').forEach(function (item) { item.remove(); });
            conversations.slice(0, 8).forEach(function (item) {
                const button = document.createElement('button');
                button.className = 'agent-chat-history-item';
                button.type = 'button';
                button.innerHTML = '<i class="ri-message-3-line"></i><span></span><small></small><span class="agent-chat-history-item__delete" role="button" aria-label="Delete chat" title="Delete chat"><i class="ri-close-line"></i></span>';
                button.querySelector('span').textContent = item.title;
                button.querySelector('small').textContent = new Date(item.updated_at).toLocaleDateString();
                button.addEventListener('click', function () { openConversation(item.id, button); });
                button.querySelector('.agent-chat-history-item__delete').addEventListener('click', function (event) {
                    event.stopPropagation();
                    deleteConversation(item.id, button);
                });
                history.appendChild(button);
            });
        } catch (error) {}
    }

    async function deleteConversation(id, button) {
        const confirmed = await confirmDelete();
        if (!confirmed) return;
        try {
            const response = await fetch(window.agentApiBase + '/ai/conversations/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            if (!response.ok) return;
            button.remove();
            if (conversationId === id) clearMessages();
        } catch (error) {}
    }

    async function openConversation(id, button) {
        try {
            const response = await fetch(window.agentApiBase + '/ai/conversations/' + id + '/messages', { headers: { Accept: 'application/json' } });
            if (!response.ok) return;
            const messages = await response.json();
            conversation.querySelectorAll('.agent-chat-message').forEach(function (message) { message.remove(); });
            welcome.hidden = true;
            conversationId = id;
            messages.forEach(function (item) {
                let chart = item.chart;
                if (typeof chart === 'string') {
                    try { chart = JSON.parse(chart); } catch (error) { chart = null; }
                }
                const messageEl = addMessage(item.content, item.role === 'user', chart);
                let plan = item.plan;
                if (typeof plan === 'string') {
                    try { plan = JSON.parse(plan); } catch (error) { plan = null; }
                }
                if (plan && plan.sql) renderSqlQuery(messageEl, plan.sql);
            });
            document.querySelectorAll('.agent-chat-history-item').forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            closeSidebar();
        } catch (error) {}
    }

    function updateHistoryTitle(value) {
        const active = document.querySelector('.agent-chat-history-item.is-active span');
        if (active && active.textContent === 'Getting started with AI') active.textContent = value.slice(0, 32);
    }

    function renderDemoChart(message, chart) {
        if (!chart || !Array.isArray(chart.labels) || !Array.isArray(chart.series) || !window.Chart) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'agent-chat-chart';
        const canvas = document.createElement('canvas');
        wrapper.appendChild(canvas);
        message.querySelector('.agent-chat-message__body').appendChild(wrapper);
        new Chart(canvas, {
            type: chart.type || 'bar',
            data: { labels: chart.labels, datasets: [{ label: 'Tickets', data: chart.series, backgroundColor: ['#2459d6', '#0f9f9a', '#f3b841', '#e57973'], borderRadius: 6, borderSkipped: false }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }

    function updateDebug(payload) {
        const debug = document.getElementById('agentChatDebug');
        if (debug) debug.textContent = JSON.stringify({ plan: payload.plan, result: payload.result, chart: payload.chart }, null, 2);
    }

    composer.addEventListener('submit', function (event) { event.preventDefault(); sendMessage(); });
    input.addEventListener('keydown', function (event) { if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); sendMessage(); } });
    input.addEventListener('input', function () { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 110) + 'px'; });
    document.querySelectorAll('[data-prompt]').forEach(function (button) { button.addEventListener('click', function () { sendMessage(button.dataset.prompt); }); });
    menuButton.addEventListener('click', function () { sidebar.classList.add('is-open'); });
    closeButton.addEventListener('click', closeSidebar);
    newChatButton.addEventListener('click', clearMessages);
    clearButton.addEventListener('click', deleteCurrentConversation);
    loadHistory();
});
