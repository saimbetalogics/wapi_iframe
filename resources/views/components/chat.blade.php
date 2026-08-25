<div id="wapi-chat">
    <div id="chat-header">
        <div class="h-avatar" style="background:#e91e63">AJ</div>
        <div class="h-info">
            <div class="h-name">Alice Johnson</div>
            <div class="h-status">
                <span class="online-dot"></span> online
            </div>
        </div>
        <div class="h-icons">
            <button class="icon-btn" title="More options">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="messages-area"></div>

    <div id="emoji-picker"></div>

    <div id="chat-input-bar">
        <button class="input-icon-btn" id="emoji-btn" title="Emoji">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                <path d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.962 1.44 1.962zm5.614 0c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.962 1.44 1.962zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.598-6.614c-.798 1.76-2.174 2.744-3.598 2.744s-2.8-.984-3.598-2.744a.852.852 0 0 0-.8-.49.852.852 0 0 0-.8 1.214C7.882 16.36 9.842 17.6 12 17.6c2.158 0 4.118-1.24 5.198-3.49a.852.852 0 0 0-.8-1.214.852.852 0 0 0-.8.49z"/>
            </svg>
        </button>
        <button class="input-icon-btn" id="attach-btn" title="Attach file">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                <path d="M1.816 15.556v.001c0 1.502.584 2.912 1.646 3.972s2.472 1.647 3.974 1.647a5.58 5.58 0 0 0 3.972-1.645l9.547-9.548c.769-.768 1.147-1.767 1.058-2.817-.079-.968-.548-1.927-1.319-2.698-1.594-1.592-4.068-1.711-5.517-.262l-7.916 7.915c-.881.881-.792 2.25.214 3.261.959.958 2.423 1.053 3.263.215l5.511-5.512c.28-.28.267-.722.053-.936l-.244-.244c-.191-.191-.567-.349-.957.04l-5.506 5.506c-.18.18-.635.127-.976-.214-.098-.097-.576-.613-.213-.973l7.915-7.917c.818-.817 2.267-.699 3.23.262.5.501.802 1.1.849 1.685.051.573-.156 1.111-.589 1.543l-9.547 9.549a3.97 3.97 0 0 1-2.829 1.171 3.975 3.975 0 0 1-2.83-1.173 3.973 3.973 0 0 1-1.172-2.828c0-1.071.415-2.076 1.172-2.83l7.209-7.211c.157-.157.264-.579.028-.814L11.5 4.36a.572.572 0 0 0-.834.018L3.458 11.79a5.567 5.567 0 0 0-1.642 3.766z"/>
            </svg>
        </button>
        <div id="msg-input-wrap">
            <textarea id="msg-input" placeholder="Type a message" rows="1" autocomplete="off"></textarea>
        </div>
        <button id="send-btn" class="mic">
            <svg class="send-icon" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
            <svg class="mic-icon" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path d="M12 15c1.66 0 2.99-1.34 2.99-3L15 6c0-1.66-1.34-3-3-3S9 4.34 9 6v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 15 6.7 12H5c0 3.42 2.72 6.23 6 6.72V22h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
            </svg>
        </button>
    </div>
</div>

<script>
(function () {
'use strict';

const EMOJIS = [
    '😀','😂','🥰','😎','🤔','🙄','😅','😭','🤣','😍',
    '😊','😋','🤩','😘','😏','😇','🤗','😤','🥳','😜',
    '👍','👏','🙏','🤝','✌️','🫂','💪','🔥','❤️','💯',
    '✨','🎉','🥺','😱','🤦','🙆','💬','🚀','📝','⭐',
];

const AUTO_REPLIES = [
    "Got it! 👍",
    "Sure, sounds great!",
    "I'll get back to you shortly.",
    "Thanks for letting me know 😊",
    "Interesting! Tell me more 🤔",
    "✅ Done!",
    "On it! 🚀",
    "Absolutely, no problem at all!",
    "Let me check and confirm.",
    "That works for me! 🎉",
    "Perfect, thanks! 🙌",
    "Noted! 📝",
    "Haha, nice one 😂",
    "That's awesome! ✨",
    "Makes sense 👌",
    "I was just thinking the same thing!",
];

const DEMO_MESSAGES = [
    { out: false, text: "Hey! Are you free this evening? 👋",                         time: "9:10 AM",  date: "Yesterday", status: "read" },
    { out: true,  text: "Yeah I should be! What's up?",                               time: "9:13 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "We're planning a small get-together at Sarah's place. You should come! 🎉", time: "9:15 AM", date: "Yesterday", status: "read" },
    { out: true,  text: "Oh that sounds fun 😄 What time does it start?",             time: "9:17 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "Around 7 PM. There'll be food, games, the whole deal 🍕🎮",  time: "9:18 AM",  date: "Yesterday", status: "read" },
    { out: true,  text: "I'm in! Should I bring anything?",                           time: "9:20 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "Just yourself 😄 Oh, and maybe some drinks if you want!",    time: "9:21 AM",  date: "Yesterday", status: "read" },
    { out: true,  text: "Will do! Looking forward to it 🥤",                          time: "9:22 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "Same! It's been a while since we all hung out 😊",           time: "9:22 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "Hey, did you end up watching that movie I recommended?",     time: "11:45 AM", date: "Today",     status: "read" },
    { out: true,  text: "Yes!! Watched it last night — it was incredible 🤩",         time: "11:50 AM", date: "Today",     status: "read" },
    { out: false, text: "Right?! The twist at the end got me 😱",                     time: "11:51 AM", date: "Today",     status: "read" },
    { out: true,  text: "I did NOT see that coming at all 😂 I had to rewind it twice just to make sure.", time: "11:52 AM", date: "Today", status: "read" },
    { out: false, text: "Haha same! The director is a genius honestly 👏",            time: "11:53 AM", date: "Today",     status: "read" },
    { out: true,  text: "Do you have more recommendations? I'm on a movie binge lately 🍿", time: "11:55 AM", date: "Today", status: "read" },
    { out: false, text: "Oh I have a whole list 😄 I'll send you one later!",         time: "11:56 AM", date: "Today",     status: "read" },
    { out: true,  text: "Perfect! Also, see you tonight 🙌",                          time: "12:00 PM", date: "Today",     status: "read" },
    { out: false, text: "Can't wait! 🎉",                                             time: "12:01 PM", date: "Today",     status: "read" },
];

let msgIdCounter = 300;

const $  = id => document.getElementById(id);
const fmt = () => new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

function esc(t) {
    return t
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\n/g, '<br>');
}

function tickHTML(status) {
    if (status === 'pending') {
        return `<span class="tick pending">
            <svg viewBox="0 0 16 11"><path d="M11.071.653l-6.383 7.17-2.75-2.75-1.5 1.5 4.25 4.25 7.883-8.669z"/></svg>
        </span>`;
    }
    if (status === 'sent') {
        return `<span class="tick sent">
            <svg viewBox="0 0 16 11"><path d="M10.91.681l-6.383 7.17-2.75-2.75-1.5 1.5 4.25 4.25 7.883-8.67z"/></svg>
        </span>`;
    }
    return `<span class="tick read">
        <svg viewBox="0 0 18 11" width="18" height="11">
            <path d="M17.394.646l-9.762 10.634L.646 4.285l1.06-1.06 5.926 5.925L16.334-.414z"/>
            <path d="M13.4.646l-5.766 6.27-.944-.944-1.06 1.06 2.004 2.003L14.46-.414z"/>
        </svg>
    </span>`;
}

function bubbleHTML(m) {
    return `
    <div class="msg-row ${m.out ? 'out' : 'in'}" data-msgid="${m.id}">
        <div class="bubble">
            <span class="msg-text">${esc(m.text)}</span>
            <span class="msg-meta">
                <span class="msg-time">${m.time}</span>
                ${m.out ? tickHTML(m.status) : ''}
            </span>
        </div>
    </div>`;
}

function renderMessages() {
    const area = $('messages-area');
    area.innerHTML = '';
    let lastDate = null;

    DEMO_MESSAGES.forEach((m, i) => {
        m.id = i + 1;
        if (m.date !== lastDate) {
            area.insertAdjacentHTML('beforeend',
                `<div class="date-sep"><span>${m.date}</span></div>`);
            lastDate = m.date;
        }
        area.insertAdjacentHTML('beforeend', bubbleHTML(m));
    });

    area.insertAdjacentHTML('beforeend', `
        <div class="msg-row in" id="typing-row" style="display:none">
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>`);

    scrollBottom();
}

function scrollBottom() {
    const a = $('messages-area');
    a.scrollTop = a.scrollHeight;
}

function sendMessage() {
    const input = $('msg-input');
    const text  = input.value.trim();
    if (!text) return;

    msgIdCounter++;
    const m = { id: msgIdCounter, text, out: true, time: fmt(), status: 'pending' };

    input.value = '';
    input.style.height = '';
    updateSendBtn();

    const area   = $('messages-area');
    const tyRow  = $('typing-row');
    const row    = document.createElement('div');
    row.className    = 'msg-row out';
    row.dataset.msgid = m.id;
    row.innerHTML = `
        <div class="bubble">
            <span class="msg-text">${esc(m.text)}</span>
            <span class="msg-meta">
                <span class="msg-time">${m.time}</span>
                ${tickHTML('pending')}
            </span>
        </div>`;
    area.insertBefore(row, tyRow);
    scrollBottom();

    setTimeout(() => updateTick(m.id, 'sent'), 700);
    setTimeout(() => updateTick(m.id, 'read'), 2000);

    setTimeout(() => showTyping(true), 2500);
    const delay = 4200 + Math.random() * 1000;
    setTimeout(() => {
        showTyping(false);
        fakeReply();
    }, delay);
}

function updateTick(id, status) {
    const row  = document.querySelector(`[data-msgid="${id}"]`);
    if (!row) return;
    const tick = row.querySelector('.tick');
    if (tick) tick.outerHTML = tickHTML(status);
}

function showTyping(show) {
    const row = $('typing-row');
    if (!row) return;
    row.style.display = show ? 'flex' : 'none';
    if (show) scrollBottom();
}

function fakeReply() {
    msgIdCounter++;
    const text = AUTO_REPLIES[Math.floor(Math.random() * AUTO_REPLIES.length)];
    const m    = { id: msgIdCounter, text, out: false, time: fmt(), status: 'read' };

    const area  = $('messages-area');
    const tyRow = $('typing-row');
    const row   = document.createElement('div');
    row.className    = 'msg-row in';
    row.dataset.msgid = m.id;
    row.innerHTML = `
        <div class="bubble">
            <span class="msg-text">${esc(m.text)}</span>
            <span class="msg-meta"><span class="msg-time">${m.time}</span></span>
        </div>`;
    area.insertBefore(row, tyRow);
    scrollBottom();
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function updateSendBtn() {
    const btn = $('send-btn');
    if ($('msg-input').value.trim()) btn.classList.remove('mic');
    else btn.classList.add('mic');
}

function buildEmojiPicker() {
    const picker = $('emoji-picker');
    EMOJIS.forEach(e => {
        const span       = document.createElement('span');
        span.textContent = e;
        span.title       = e;
        span.addEventListener('click', () => {
            const inp   = $('msg-input');
            const start = inp.selectionStart;
            const end   = inp.selectionEnd;
            inp.value   = inp.value.slice(0, start) + e + inp.value.slice(end);
            inp.selectionStart = inp.selectionEnd = start + e.length;
            inp.focus();
            autoResize(inp);
            updateSendBtn();
        });
        picker.appendChild(span);
    });
}

$('msg-input').addEventListener('input', function () {
    autoResize(this);
    updateSendBtn();
});

$('msg-input').addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

$('send-btn').addEventListener('click', function () {
    if (!this.classList.contains('mic')) sendMessage();
});

$('emoji-btn').addEventListener('click', function (e) {
    e.stopPropagation();
    $('emoji-picker').classList.toggle('open');
});

document.addEventListener('click', function (e) {
    const picker = $('emoji-picker');
    if (!picker.contains(e.target) && e.target !== $('emoji-btn')) {
        picker.classList.remove('open');
    }
});

buildEmojiPicker();
renderMessages();

})();
</script>
