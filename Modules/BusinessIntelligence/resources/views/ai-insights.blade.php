@extends('bi::layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block">
    <div class="subheader-bar"><div class="subheader-title"><h3>AI Insights</h3><p>Ask questions about the current client’s BI aggregates.</p></div></div>
    <div class="content-container">
        <div class="ui-card" style="padding:1.25rem;max-width:900px">
            <h4 style="margin:0 0 .5rem">Nexora AI Business Analyst</h4>
            <p style="color:var(--slate-500);line-height:1.6;margin-top:0">Answers use only this client’s aggregate BI metrics. Raw employee data, other client data, and credentials are never sent to the model.</p>
            <div id="conversation" class="ai-chat-messages" style="height:300px;border:1px solid var(--slate-200);border-radius:10px;margin:1rem 0">
                <div class="ai-message ai-message-bot"><div class="ai-message-content">Ask about revenue, expenses, stock alerts, purchase orders, fulfillment, or manufacturing activity.</div></div>
            </div>
            <div class="ai-chat-input-row"><input id="question" class="ai-chat-input" maxlength="1500" placeholder="For example: What should our team focus on this week?"><button id="send" class="control-btn" type="button">Ask AI</button></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const chatEndpoint = @json(route('bi.ai.chat'));
const clientScope = @json(request()->integer('client_id') ?: null);
const question = document.getElementById('question');
const send = document.getElementById('send');
const conversation = document.getElementById('conversation');
function addMessage(kind, text) { const item = document.createElement('div'); item.className = 'ai-message ai-message-' + kind; const content = document.createElement('div'); content.className = 'ai-message-content'; content.textContent = text; item.appendChild(content); conversation.appendChild(item); conversation.scrollTop = conversation.scrollHeight; return item; }
async function askAi() { const message = question.value.trim(); if (!message) return; addMessage('user', message); question.value = ''; send.disabled = true; const pending = addMessage('bot', 'Analyzing your client-scoped BI metrics…'); try { const response = await fetch(chatEndpoint + (clientScope ? '?client_id=' + clientScope : ''), {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:JSON.stringify({message})}); const data = await response.json(); pending.remove(); addMessage('bot', data.message || 'AI Insights is temporarily unavailable.'); } catch (_) { pending.remove(); addMessage('bot', 'AI Insights is temporarily unavailable. Please try again shortly.'); } finally { send.disabled = false; question.focus(); } }
send.addEventListener('click', askAi); question.addEventListener('keydown', event => { if (event.key === 'Enter') askAi(); });
</script>
@endsection
