<x-layouts.admin title="AI Query">
<style>
.ai-page{--ai-ink:#142033;--ai-muted:#708096;--ai-line:#dfe6ef;--ai-blue:#2459d6;--ai-cyan:#0f9f9a;max-width:1180px;margin:-4px auto 0}.ai-hero{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:24px}.ai-kicker{display:flex;align-items:center;gap:8px;margin-bottom:9px;color:var(--ai-cyan);font-size:11px;font-weight:800;letter-spacing:.13em;text-transform:uppercase}.ai-kicker i{width:8px;height:8px;border-radius:50%;background:#2db8a6;box-shadow:0 0 0 5px #d8f3ef}.ai-hero h2{margin:0;color:var(--ai-ink);font-size:30px;letter-spacing:-.02em}.ai-hero p{max-width:520px;margin:9px 0 0;color:var(--ai-muted);font-size:14px}.ai-status{display:flex;align-items:center;gap:8px;padding:8px 11px;border:1px solid #cfe9e5;border-radius:5px;background:#f1fbf9;color:#19736f;font-size:12px;font-weight:700;white-space:nowrap}.ai-status span{width:7px;height:7px;border-radius:50%;background:#24a893}.ai-layout{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(250px,.65fr);gap:20px}.ai-conversation,.ai-context{border:1px solid var(--ai-line);border-radius:8px;background:#fff;box-shadow:0 12px 35px #17335d0a}.ai-conversation{min-height:585px;display:flex;flex-direction:column;overflow:hidden}.ai-conversation-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #edf1f5}.ai-conversation-title{display:flex;align-items:center;gap:10px;color:var(--ai-ink);font-weight:800}.ai-spark{display:grid;place-items:center;width:29px;height:29px;border-radius:7px;background:#e8efff;color:var(--ai-blue);font-size:17px}.ai-clear{border:0;background:transparent;color:#8090a5;font-size:12px}.ai-clear:hover{color:var(--ai-blue)}.ai-messages{flex:1;min-height:330px;padding:24px 20px;overflow:auto;background:linear-gradient(180deg,#fbfcfe 0%,#fff 100%)}.ai-chart-wrap{position:relative;margin-top:14px;width:100%;min-height:220px;padding:12px;border:1px solid #edf1f5;border-radius:10px;background:#fafcff}.ai-chart-wrap canvas{width:100%!important;height:220px!important}.ai-welcome{text-align:center;padding:42px 15px 25px}.ai-welcome-mark{width:54px;height:54px;margin:0 auto 17px;display:grid;place-items:center;border-radius:15px;background:#142c4f;color:#fff;font-size:25px;box-shadow:8px 8px 0 #dce6f5}.ai-welcome h3{margin:0;color:var(--ai-ink);font-size:19px}.ai-welcome p{max-width:410px;margin:8px auto 0;color:var(--ai-muted);font-size:13px}.ai-message{display:flex;gap:10px;margin:0 0 19px;animation:message-in .25s ease both}.ai-message.user{justify-content:flex-end}.ai-message-avatar{display:grid;place-items:center;flex:0 0 28px;width:28px;height:28px;border-radius:8px;background:#e8efff;color:var(--ai-blue);font-size:13px;font-weight:800}.ai-message.user .ai-message-avatar{order:2;background:#142c4f;color:#fff}.ai-bubble{max-width:min(78%,560px);padding:11px 14px;border-radius:4px 13px 13px 13px;background:#f0f4fa;color:#25344a;line-height:1.55;font-size:13px;white-space:pre-wrap}.ai-message.user .ai-bubble{order:1;border-radius:13px 4px 13px 13px;background:#2459d6;color:#fff}.ai-message-meta{margin:4px 3px 0;color:#9aa7b7;font-size:10px}.ai-message.user .ai-message-meta{text-align:right}.ai-typing{display:flex;align-items:center;gap:5px;width:max-content;padding:12px 15px;border-radius:4px 13px 13px 13px;background:#f0f4fa}.ai-typing b{width:5px;height:5px;border-radius:50%;background:#7891b1;animation:typing 1s infinite}.ai-typing b:nth-child(2){animation-delay:.15s}.ai-typing b:nth-child(3){animation-delay:.3s}.ai-composer{padding:14px 16px 16px;border-top:1px solid #edf1f5}.ai-composer textarea{display:block;width:100%;min-height:64px;padding:12px 13px;border:1px solid #ced8e5;border-radius:6px;outline:0;resize:vertical;color:var(--ai-ink);background:#fff;font:13px/1.5 Figtree,Arial,sans-serif}.ai-composer textarea:focus{border-color:#6d93e8;box-shadow:0 0 0 3px #e8efff}.ai-composer-foot{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:10px}.ai-hint{color:#97a4b4;font-size:11px}.ai-send{display:inline-flex;align-items:center;gap:8px;border:0;border-radius:5px;padding:10px 15px;background:#142c4f;color:#fff;font-size:12px;font-weight:800}.ai-send:hover{background:#2459d6}.ai-send:disabled{opacity:.55;cursor:wait}.ai-send svg{width:14px;height:14px}.ai-context{align-self:start;padding:20px;background:#142c4f;color:#dbe7f8;box-shadow:12px 14px 0 #e3ebf6}.ai-context h3{margin:0 0 6px;color:#fff;font-size:16px}.ai-context>p{margin:0 0 20px;color:#9fb2cd;font-size:12px;line-height:1.55}.ai-fields{display:grid;gap:13px}.ai-field label{display:block;margin-bottom:6px;color:#9fb2cd;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.ai-field input{width:100%;padding:10px 11px;border:1px solid #355173;border-radius:4px;outline:0;background:#1a3557;color:#fff;font-size:13px}.ai-field input:focus{border-color:#7da5e9}.ai-suggestions{margin-top:24px;padding-top:18px;border-top:1px solid #355173}.ai-suggestions h4{margin:0 0 11px;color:#fff;font-size:11px;letter-spacing:.08em;text-transform:uppercase}.ai-suggestion{display:block;width:100%;margin:7px 0;padding:9px 10px;border:1px solid #355173;border-radius:4px;background:#183253;color:#c2d1e5;text-align:left;font-size:12px}.ai-suggestion:hover{border-color:#7da5e9;color:#fff;background:#204064}.ai-error{display:none;margin:0 20px 14px;padding:10px 12px;border:1px solid #fecaca;border-radius:5px;background:#fff1f2;color:#b42318;font-size:12px}.ai-error.show{display:block}.ai-debug{margin-top:12px;border-top:1px solid #edf1f5}.ai-debug summary{padding:12px 20px;color:#8a98aa;font-size:11px;cursor:pointer}.ai-debug pre{max-height:180px;margin:0;padding:0 20px 15px;overflow:auto;color:#64748b;font-size:10px;white-space:pre-wrap}@keyframes message-in{from{opacity:0;transform:translateY(7px)}to{opacity:1;transform:none}}@keyframes typing{0%,60%,100%{opacity:.3;transform:translateY(0)}30%{opacity:1;transform:translateY(-3px)}}@media(max-width:900px){.ai-layout{grid-template-columns:1fr}.ai-context{order:-1;box-shadow:none}.ai-context>p{max-width:500px}.ai-fields{grid-template-columns:1fr 1fr}.ai-suggestions{display:none}}@media(max-width:600px){.ai-page{margin:0}.ai-hero{align-items:flex-start;flex-direction:column;margin-bottom:18px}.ai-hero h2{font-size:25px}.ai-status{align-self:flex-start}.ai-conversation{min-height:620px}.ai-messages{padding:18px 14px}.ai-conversation-head{padding:14px}.ai-bubble{max-width:84%}.ai-composer{padding:12px}.ai-composer-foot{align-items:flex-end}.ai-hint{max-width:180px}.ai-fields{grid-template-columns:1fr}}
</style>

<section class="ai-page" id="aiChat">
    <div class="ai-hero">
        <div>
            <div class="ai-kicker"><i></i> Live data assistant</div>
            <h2>Ask your workspace.</h2>
            <p>Turn a plain-language question into a focused answer from your ticket data.</p>
        </div>
        <div class="ai-status"><span></span> Ollama ready</div>
    </div>

    <div class="ai-layout">
        <section class="ai-conversation" aria-label="AI conversation">
            <div class="ai-conversation-head">
                <div class="ai-conversation-title"><span class="ai-spark">✦</span> Ticket intelligence</div>
                <button class="ai-clear" type="button" id="clearChat">Clear chat</button>
            </div>
            <div class="ai-messages" id="messages">
                <div class="ai-welcome" id="welcomeState">
                    <div class="ai-welcome-mark">✦</div>
                    <h3>What would you like to know?</h3>
                    <p>Ask about tickets, priorities, statuses, or time periods. I will read the data and explain the result.</p>
                </div>
            </div>
            <div class="ai-error" id="errorBox" role="alert"></div>
            <form class="ai-composer" id="chatForm">
                <textarea id="question" maxlength="5000" placeholder="Try: Mere is month kitne tickets close hue?" aria-label="Your question" required></textarea>
                <div class="ai-composer-foot">
                    <span class="ai-hint">Press Ctrl + Enter to send</span>
                    <button class="ai-send" type="submit" id="sendButton"><span>Ask AI</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
                </div>
            </form>
            <details class="ai-debug"><summary>View query details</summary><pre id="debugOutput">No query has been run yet.</pre></details>
        </section>

        <aside class="ai-context">
            <h3>Query context</h3>
            <p>These temporary IDs scope the answer until authentication is connected.</p>
            <div class="ai-fields">
                <div class="ai-field"><label for="companyId">Company ID</label><input id="companyId" type="number" min="1" value="1" required></div>
                <div class="ai-field"><label for="userId">User ID</label><input id="userId" type="number" min="1" value="1" required></div>
            </div>
            <div class="ai-suggestions">
                <h4>Try asking</h4>
                <button class="ai-suggestion" type="button">Mere total tickets kitne hain?</button>
                <button class="ai-suggestion" type="button">How many tickets are closed this month?</button>
                <button class="ai-suggestion" type="button">Show my high priority tickets</button>
            </div>
        </aside>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
    const form = document.getElementById('chatForm');
    const question = document.getElementById('question');
    const messages = document.getElementById('messages');
    const welcomeState = document.getElementById('welcomeState');
    const sendButton = document.getElementById('sendButton');
    const errorBox = document.getElementById('errorBox');
    const debugOutput = document.getElementById('debugOutput');
    const clearChat = document.getElementById('clearChat');
    const companyId = document.getElementById('companyId');
    const userId = document.getElementById('userId');

    const addMessage = (text, type) => {
        welcomeState?.remove();
        const item = document.createElement('div');
        item.className = `ai-message ${type}`;
        item.innerHTML = `<div class="ai-message-avatar">${type === 'user' ? 'You' : '✦'}</div><div><div class="ai-bubble"></div><div class="ai-message-meta">${type === 'user' ? 'You' : 'AI assistant'}</div></div>`;
        item.querySelector('.ai-bubble').textContent = text;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
        return item;
    };

    const renderChart = (chart, container) => {
        if (!chart || !chart.labels || !chart.series || typeof Chart === 'undefined') {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'ai-chart-wrap';

        const canvas = document.createElement('canvas');
        wrapper.appendChild(canvas);
        container.querySelector('.ai-bubble')?.after(wrapper);

        const palette = ['#2459d6', '#0f9f9a', '#f3b841', '#e57973', '#7a5af8', '#4bb44b'];

        new Chart(canvas, {
            type: chart.type || 'bar',
            data: {
                labels: chart.labels,
                datasets: [{
                    label: 'Tickets',
                    data: chart.series,
                    backgroundColor: chart.labels.map((_, index) => palette[index % palette.length]),
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                    },
                },
            },
        });
    };

    const setBusy = (busy) => {
        sendButton.disabled = busy;
        sendButton.querySelector('span').textContent = busy ? 'Thinking...' : 'Ask AI';
        question.disabled = busy;
    };

    const addTyping = () => {
        const item = document.createElement('div');
        item.className = 'ai-message';
        item.id = 'typingMessage';
        item.innerHTML = '<div class="ai-message-avatar">✦</div><div class="ai-typing"><b></b><b></b><b></b></div>';
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    };

    const ask = async (event) => {
        event?.preventDefault();
        const value = question.value.trim();
        if (!value || sendButton.disabled) return;
        errorBox.classList.remove('show');
        addMessage(value, 'user');
        question.value = '';
        setBusy(true);
        addTyping();

        try {
            const response = await fetch('/api/ai/query/plan', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                body: JSON.stringify({question: value, company_id: Number(companyId.value), user_id: Number(userId.value)})
            });
            const data = await response.json();
            document.getElementById('typingMessage')?.remove();
            if (!response.ok || !data.success) throw new Error(data.message || 'The assistant could not complete this query.');

            const assistantItem = addMessage(data.answer || 'The query completed, but no answer was returned.', 'assistant');
            renderChart(data.chart, assistantItem);
            debugOutput.textContent = JSON.stringify({plan: data.plan, result: data.result, chart: data.chart}, null, 2);
        } catch (error) {
            document.getElementById('typingMessage')?.remove();
            errorBox.textContent = error.message;
            errorBox.classList.add('show');
        } finally {
            setBusy(false);
            question.focus();
        }
    };

    form.addEventListener('submit', ask);
    question.addEventListener('keydown', (event) => { if (event.ctrlKey && event.key === 'Enter') ask(event); });
    clearChat.addEventListener('click', () => { messages.innerHTML = ''; messages.appendChild(welcomeState); errorBox.classList.remove('show'); debugOutput.textContent = 'No query has been run yet.'; });
    document.querySelectorAll('.ai-suggestion').forEach((button) => button.addEventListener('click', () => { question.value = button.textContent.trim(); question.focus(); }));
})();
</script>
</x-layouts.admin>
