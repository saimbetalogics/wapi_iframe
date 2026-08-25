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
        <div id="normal-input-wrap">
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
        </div>

        <div id="voice-rec-bar">
            <button class="input-icon-btn danger" id="discard-rec-btn" title="Discard recording">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
            </button>
            <div class="rec-timer-wrap">
                <span class="rec-dot"></span>
                <span id="rec-timer">0:00</span>
            </div>
            <div class="rec-visualizer-wrap">
                <canvas id="rec-visualizer" width="140" height="26"></canvas>
            </div>
            <button class="input-icon-btn" id="pause-rec-btn" title="Pause / Resume recording">
                <svg class="pause-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
                <svg class="resume-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor" style="display:none">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
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
];

const SAMPLE_PEAKS_IN  = [0.25, 0.4, 0.7, 0.9, 0.6, 0.3, 0.8, 1.0, 0.65, 0.4, 0.8, 0.5, 0.3, 0.6, 0.9, 0.7, 0.4, 0.2, 0.5, 0.8, 0.6, 0.3, 0.7, 0.5, 0.4, 0.2, 0.6, 0.3];
const SAMPLE_PEAKS_OUT = [0.3, 0.6, 0.4, 0.8, 1.0, 0.7, 0.5, 0.3, 0.6, 0.9, 0.4, 0.2, 0.7, 0.8, 0.5, 0.9, 0.6, 0.3, 0.5, 0.7, 0.4, 0.2, 0.6, 0.8, 0.5, 0.3, 0.4, 0.2];

const DEMO_MESSAGES = [
    { out: false, text: "Hey! Are you free this evening? 👋",                         time: "9:10 AM",  date: "Yesterday", status: "read" },
    { out: true,  text: "Yeah I should be! What's up?",                               time: "9:13 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "We're planning a small get-together at Sarah's place. You should come! 🎉", time: "9:15 AM", date: "Yesterday", status: "read" },
    { out: true,  text: "Oh that sounds fun 😄 What time does it start?",             time: "9:17 AM",  date: "Yesterday", status: "read" },
    { out: false, type: "voice", duration: 7, peaks: SAMPLE_PEAKS_IN,                time: "9:18 AM",  date: "Yesterday", status: "read" },
    { out: true,  text: "Got it! Thanks for sending the audio details 🎧",            time: "9:20 AM",  date: "Yesterday", status: "read" },
    { out: false, text: "Hey, did you end up watching that movie I recommended?",     time: "11:45 AM", date: "Today",     status: "read" },
    { out: true,  type: "voice", duration: 5, peaks: SAMPLE_PEAKS_OUT,               time: "11:52 AM", date: "Today",     status: "read" },
    { out: false, text: "Can't wait to hear more! 🎉",                                time: "12:01 PM", date: "Today",     status: "read" },
];

let msgIdCounter = 400;

const $  = id => document.getElementById(id);
const fmt = () => new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

function fmtDuration(s) {
    s = Math.round(s || 0);
    const m = Math.floor(s / 60);
    const sec = s % 60;
    return `${m}:${sec < 10 ? '0' : ''}${sec}`;
}

function esc(t) {
    return (t || '')
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

function generatePeaksHTML(peaks) {
    const p = (peaks && peaks.length >= 10) ? peaks : SAMPLE_PEAKS_OUT;
    return p.map(val => {
        const heightPct = Math.max(15, Math.min(100, Math.round(val * 100)));
        return `<span class="vb-bar" style="height:${heightPct}%"></span>`;
    }).join('');
}

function bubbleHTML(m) {
    if (m.type === 'voice') {
        const durationStr = fmtDuration(m.duration || 5);
        return `
        <div class="msg-row ${m.out ? 'out' : 'in'}" data-msgid="${m.id}">
            <div class="bubble voice-bubble">
                <div class="vb-avatar">
                    ${m.out
                        ? `<div class="vba-circle out" title="You">
                             <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M12 15c1.66 0 2.99-1.34 2.99-3L15 6c0-1.66-1.34-3-3-3S9 4.34 9 6v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 15 6.7 12H5c0 3.42 2.72 6.23 6 6.72V22h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
                             </svg>
                           </div>`
                        : `<div class="vba-circle in" title="Alice">AJ</div>`
                    }
                </div>
                <div class="vb-content">
                    <div class="vb-main">
                        <button class="vb-play-btn" data-msgid="${m.id}" title="Play voice message">
                            <svg class="play-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg class="pause-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor" style="display:none">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>
                        <div class="vb-waveform-container" data-msgid="${m.id}" title="Seek audio">
                            <div class="vb-waveform-bars" data-msgid="${m.id}">
                                ${generatePeaksHTML(m.peaks)}
                            </div>
                            <div class="vb-waveform-progress" data-msgid="${m.id}" style="width: 0%"></div>
                            <div class="vb-waveform-handle" data-msgid="${m.id}" style="left: 0%"></div>
                        </div>
                    </div>
                    <div class="vb-footer">
                        <span class="vb-time-disp" data-msgid="${m.id}">${durationStr}</span>
                        <button class="vb-speed-btn" data-msgid="${m.id}" title="Playback speed">1x</button>
                    </div>
                </div>
                <span class="msg-meta voice-meta">
                    <span class="msg-time">${m.time}</span>
                    ${m.out ? tickHTML(m.status) : ''}
                </span>
            </div>
        </div>`;
    }

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

const audioBlobs = new Map();
const messageMetaData = new Map();
let activePlayer = null;

function createSyntheticAudioBlob(durationSec = 4) {
    const sampleRate = 22050;
    const numSamples = sampleRate * durationSec;
    const buffer = new Float32Array(numSamples);
    for (let i = 0; i < numSamples; i++) {
        const t = i / sampleRate;
        buffer[i] = (Math.sin(2 * Math.PI * 440 * t) * 0.3 +
                     Math.sin(2 * Math.PI * 554.37 * t) * 0.25 +
                     Math.sin(2 * Math.PI * 659.25 * t) * 0.2) *
                     Math.exp(-t * 0.25) * (0.85 + 0.15 * Math.sin(10 * t));
    }
    const wavBuffer = new ArrayBuffer(44 + numSamples * 2);
    const view = new DataView(wavBuffer);
    const writeString = (offset, string) => {
        for (let i = 0; i < string.length; i++) {
            view.setUint8(offset + i, string.charCodeAt(i));
        }
    };
    writeString(0, 'RIFF');
    view.setUint32(4, 36 + numSamples * 2, true);
    writeString(8, 'WAVE');
    writeString(12, 'fmt ');
    view.setUint32(16, 16, true);
    view.setUint16(20, 1, true);
    view.setUint16(22, 1, true);
    view.setUint32(24, sampleRate, true);
    view.setUint32(28, sampleRate * 2, true);
    view.setUint16(32, 2, true);
    view.setUint16(34, 16, true);
    writeString(36, 'data');
    view.setUint32(40, numSamples * 2, true);
    let offset = 44;
    for (let i = 0; i < numSamples; i++, offset += 2) {
        const s = Math.max(-1, Math.min(1, buffer[i]));
        view.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
    }
    return new Blob([wavBuffer], { type: 'audio/wav' });
}

function getAudioUrl(msgId, duration = 5) {
    if (audioBlobs.has(msgId)) {
        const item = audioBlobs.get(msgId);
        return typeof item === 'string' ? item : URL.createObjectURL(item);
    }
    const blob = createSyntheticAudioBlob(duration);
    const url  = URL.createObjectURL(blob);
    audioBlobs.set(msgId, url);
    return url;
}

function stopActivePlayer() {
    if (!activePlayer) return;
    try {
        activePlayer.audio.pause();
    } catch(e) {}

    const oldId = activePlayer.msgId;
    activePlayer = null;

    updatePlayerUI(oldId, 0, false);
}

function updatePlayerUI(msgId, progressPct, isPlaying) {
    const row = document.querySelector(`.msg-row[data-msgid="${msgId}"]`);
    if (!row) return;

    const playBtn = row.querySelector('.vb-play-btn');
    if (playBtn) {
        const pIcon = playBtn.querySelector('.play-icon');
        const pauseIcon = playBtn.querySelector('.pause-icon');
        if (isPlaying) {
            if (pIcon) pIcon.style.display = 'none';
            if (pauseIcon) pauseIcon.style.display = 'block';
        } else {
            if (pIcon) pIcon.style.display = 'block';
            if (pauseIcon) pauseIcon.style.display = 'none';
        }
    }

    const progressEl = row.querySelector('.vb-waveform-progress');
    const handleEl   = row.querySelector('.vb-waveform-handle');
    const bars       = row.querySelectorAll('.vb-bar');

    const pct = Math.max(0, Math.min(100, progressPct));
    if (progressEl) progressEl.style.width = pct + '%';
    if (handleEl) handleEl.style.left = pct + '%';

    if (bars && bars.length) {
        const playedCount = Math.floor((pct / 100) * bars.length);
        bars.forEach((bar, idx) => {
            if (idx <= playedCount && pct > 0) bar.classList.add('played');
            else bar.classList.remove('played');
        });
    }

    const meta = messageMetaData.get(msgId) || { duration: 5 };
    const timeDisp = row.querySelector('.vb-time-disp');
    if (timeDisp) {
        if (isPlaying && activePlayer && activePlayer.msgId === msgId) {
            const curTime = activePlayer.audio.currentTime;
            timeDisp.textContent = `${fmtDuration(curTime)} / ${fmtDuration(meta.duration)}`;
        } else {
            timeDisp.textContent = fmtDuration(meta.duration);
        }
    }
}

function playVoiceMessage(msgId) {
    const meta = messageMetaData.get(msgId) || { duration: 5, speed: 1 };

    if (activePlayer && activePlayer.msgId === msgId) {
        if (activePlayer.isPlaying) {
            activePlayer.audio.pause();
            activePlayer.isPlaying = false;
            updatePlayerUI(msgId, (activePlayer.audio.currentTime / (activePlayer.audio.duration || meta.duration)) * 100, false);
        } else {
            activePlayer.audio.play();
            activePlayer.isPlaying = true;
            updatePlayerUI(msgId, (activePlayer.audio.currentTime / (activePlayer.audio.duration || meta.duration)) * 100, true);
        }
        return;
    }

    if (activePlayer) {
        stopActivePlayer();
    }

    const url = getAudioUrl(msgId, meta.duration);
    const audio = new Audio(url);
    audio.playbackRate = meta.speed || 1;

    activePlayer = {
        msgId,
        audio,
        speed: meta.speed || 1,
        isPlaying: true
    };

    audio.addEventListener('timeupdate', () => {
        if (!activePlayer || activePlayer.msgId !== msgId) return;
        const dur = audio.duration || meta.duration;
        const pct = (audio.currentTime / dur) * 100;
        updatePlayerUI(msgId, pct, true);
    });

    audio.addEventListener('ended', () => {
        if (activePlayer && activePlayer.msgId === msgId) {
            activePlayer = null;
        }
        updatePlayerUI(msgId, 0, false);
    });

    audio.play().then(() => {
        updatePlayerUI(msgId, 0, true);
    }).catch(err => {
        console.warn('Audio play fallback:', err);
        updatePlayerUI(msgId, 0, false);
    });
}

function seekVoiceMessage(msgId, clickPct) {
    const meta = messageMetaData.get(msgId) || { duration: 5 };
    if (!activePlayer || activePlayer.msgId !== msgId) {
        playVoiceMessage(msgId);
    }
    if (activePlayer && activePlayer.msgId === msgId) {
        const dur = activePlayer.audio.duration || meta.duration;
        activePlayer.audio.currentTime = (clickPct / 100) * dur;
        updatePlayerUI(msgId, clickPct, activePlayer.isPlaying);
    }
}

function togglePlaybackSpeed(msgId) {
    const meta = messageMetaData.get(msgId) || { duration: 5, speed: 1 };
    const speeds = [1, 1.5, 2];
    const curIdx = speeds.indexOf(meta.speed || 1);
    const nextSpeed = speeds[(curIdx + 1) % speeds.length];
    meta.speed = nextSpeed;
    messageMetaData.set(msgId, meta);

    const btn = document.querySelector(`.msg-row[data-msgid="${msgId}"] .vb-speed-btn`);
    if (btn) btn.textContent = `${nextSpeed}x`;

    if (activePlayer && activePlayer.msgId === msgId) {
        activePlayer.audio.playbackRate = nextSpeed;
        activePlayer.speed = nextSpeed;
    }
}

function renderMessages() {
    const area = $('messages-area');
    area.innerHTML = '';
    let lastDate = null;

    DEMO_MESSAGES.forEach((m, i) => {
        m.id = i + 1;
        messageMetaData.set(m.id, { duration: m.duration || 5, speed: 1, peaks: m.peaks || SAMPLE_PEAKS_OUT });
        if (m.date !== lastDate) {
            area.insertAdjacentHTML('beforeend', `<div class="date-sep"><span>${m.date}</span></div>`);
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

let isRecording = false;
let isPausedRecording = false;
let mediaRecorder = null;
let recordedChunks = [];
let recStartTime = 0;
let recElapsedMs = 0;
let recTimerInterval = null;
let audioContext = null;
let analyserNode = null;
let animFrameId = null;
let recordedPeaks = [];

function startRecording() {
    isRecording = true;
    isPausedRecording = false;
    recordedChunks = [];
    recordedPeaks = [];
    recElapsedMs = 0;
    recStartTime = Date.now();

    $('normal-input-wrap').style.display = 'none';
    $('voice-rec-bar').style.display = 'flex';
    $('send-btn').classList.remove('mic');
    $('send-btn').classList.add('recording');
    $('rec-timer').textContent = '0:00';

    const pauseBtn = $('pause-rec-btn');
    pauseBtn.querySelector('.pause-icon').style.display = 'block';
    pauseBtn.querySelector('.resume-icon').style.display = 'none';

    clearInterval(recTimerInterval);
    recTimerInterval = setInterval(() => {
        if (!isPausedRecording) {
            recElapsedMs += 200;
            $('rec-timer').textContent = fmtDuration(recElapsedMs / 1000);
        }
    }, 200);

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
            try {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = e => {
                    if (e.data.size > 0) recordedChunks.push(e.data);
                };
                mediaRecorder.start(100);

                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const source = audioContext.createMediaStreamSource(stream);
                analyserNode = audioContext.createAnalyser();
                analyserNode.fftSize = 64;
                source.connect(analyserNode);

                drawVisualizer();
            } catch (err) {
                setupFallbackVisualizer();
            }
        }).catch(err => {
            console.warn('Microphone permission denied/unavailable. Using synthetic visualizer:', err);
            setupFallbackVisualizer();
        });
    } else {
        setupFallbackVisualizer();
    }
}

function setupFallbackVisualizer() {
    drawSyntheticVisualizer();
}

function drawVisualizer() {
    const canvas = $('rec-visualizer');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const dataArray = new Uint8Array(analyserNode.frequencyBinCount);

    function renderFrame() {
        if (!isRecording) return;
        animFrameId = requestAnimationFrame(renderFrame);

        if (!isPausedRecording && analyserNode) {
            analyserNode.getByteFrequencyData(dataArray);
            let sum = 0;
            for (let i = 0; i < dataArray.length; i++) sum += dataArray[i];
            const avg = sum / (dataArray.length * 255);
            if (Math.random() < 0.3) recordedPeaks.push(Math.max(0.2, avg));
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const barWidth = 3;
        const gap = 2;
        const numBars = Math.floor(canvas.width / (barWidth + gap));

        for (let i = 0; i < numBars; i++) {
            const val = dataArray[i % dataArray.length] / 255;
            const h = isPausedRecording ? 4 : Math.max(3, val * canvas.height);
            const x = i * (barWidth + gap);
            const y = (canvas.height - h) / 2;

            ctx.fillStyle = '#00a884';
            ctx.beginPath();
            ctx.roundRect ? ctx.roundRect(x, y, barWidth, h, 2) : ctx.fillRect(x, y, barWidth, h);
            ctx.fill();
        }
    }
    renderFrame();
}

function drawSyntheticVisualizer() {
    const canvas = $('rec-visualizer');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    function renderFrame() {
        if (!isRecording) return;
        animFrameId = requestAnimationFrame(renderFrame);

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const barWidth = 3;
        const gap = 2;
        const numBars = Math.floor(canvas.width / (barWidth + gap));
        const time = Date.now() / 150;

        for (let i = 0; i < numBars; i++) {
            const val = (Math.sin(time + i * 0.4) + 1) / 2;
            const h = isPausedRecording ? 4 : Math.max(3, val * canvas.height * 0.85);
            const x = i * (barWidth + gap);
            const y = (canvas.height - h) / 2;

            if (!isPausedRecording && i % 3 === 0 && Math.random() < 0.2) {
                recordedPeaks.push(Math.max(0.25, val));
            }

            ctx.fillStyle = '#00a884';
            ctx.beginPath();
            ctx.roundRect ? ctx.roundRect(x, y, barWidth, h, 2) : ctx.fillRect(x, y, barWidth, h);
            ctx.fill();
        }
    }
    renderFrame();
}

function pauseRecording() {
    if (!isRecording) return;
    isPausedRecording = !isPausedRecording;
    const pauseBtn = $('pause-rec-btn');
    if (isPausedRecording) {
        pauseBtn.querySelector('.pause-icon').style.display = 'none';
        pauseBtn.querySelector('.resume-icon').style.display = 'block';
        if (mediaRecorder && mediaRecorder.state === 'recording') mediaRecorder.pause();
    } else {
        pauseBtn.querySelector('.pause-icon').style.display = 'block';
        pauseBtn.querySelector('.resume-icon').style.display = 'none';
        if (mediaRecorder && mediaRecorder.state === 'paused') mediaRecorder.resume();
    }
}

function stopRecording(discard = false) {
    if (!isRecording) return;
    isRecording = false;
    isPausedRecording = false;

    clearInterval(recTimerInterval);
    if (animFrameId) cancelAnimationFrame(animFrameId);
    if (audioContext) {
        try { audioContext.close(); } catch(e) {}
    }

    const durationSec = Math.max(1, Math.round(recElapsedMs / 1000));

    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        try { mediaRecorder.stop(); } catch(e) {}
    }

    $('voice-rec-bar').style.display = 'none';
    $('normal-input-wrap').style.display = 'flex';
    $('send-btn').classList.remove('recording');
    updateSendBtn();

    if (discard) {
        recordedChunks = [];
        recordedPeaks = [];
        return;
    }

    msgIdCounter++;
    const newMsgId = msgIdCounter;

    let blob = null;
    if (recordedChunks.length > 0) {
        blob = new Blob(recordedChunks, { type: mediaRecorder.mimeType || 'audio/webm' });
    } else {
        blob = createSyntheticAudioBlob(durationSec);
    }
    audioBlobs.set(newMsgId, URL.createObjectURL(blob));

    let peaks = recordedPeaks.slice(-28);
    while (peaks.length < 28) {
        peaks.push(Math.min(1, Math.max(0.2, Math.random())));
    }
    messageMetaData.set(newMsgId, { duration: durationSec, speed: 1, peaks });

    const m = {
        id: newMsgId,
        out: true,
        type: 'voice',
        duration: durationSec,
        peaks,
        time: fmt(),
        status: 'pending'
    };

    const area  = $('messages-area');
    const tyRow = $('typing-row');
    const row   = document.createElement('div');
    row.className    = 'msg-row out';
    row.dataset.msgid = m.id;
    row.innerHTML    = bubbleHTML(m);
    area.insertBefore(row, tyRow);
    scrollBottom();

    setTimeout(() => updateTick(m.id, 'sent'), 700);
    setTimeout(() => updateTick(m.id, 'read'), 2000);

    setTimeout(() => showTyping(true), 2500);
    const delay = 4000 + Math.random() * 1000;
    setTimeout(() => {
        showTyping(false);
        fakeVoiceReply();
    }, delay);
}

function fakeVoiceReply() {
    msgIdCounter++;
    const newId = msgIdCounter;
    const dur = Math.floor(Math.random() * 6) + 3;

    audioBlobs.set(newId, URL.createObjectURL(createSyntheticAudioBlob(dur)));
    messageMetaData.set(newId, { duration: dur, speed: 1, peaks: SAMPLE_PEAKS_IN });

    const m = {
        id: newId,
        out: false,
        type: 'voice',
        duration: dur,
        peaks: SAMPLE_PEAKS_IN,
        time: fmt(),
        status: 'read'
    };

    const area  = $('messages-area');
    const tyRow = $('typing-row');
    const row   = document.createElement('div');
    row.className    = 'msg-row in';
    row.dataset.msgid = m.id;
    row.innerHTML    = bubbleHTML(m);
    area.insertBefore(row, tyRow);
    scrollBottom();
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
    row.innerHTML = bubbleHTML(m);
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
    row.innerHTML = bubbleHTML(m);
    area.insertBefore(row, tyRow);
    scrollBottom();
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function updateSendBtn() {
    const btn = $('send-btn');
    if (isRecording) return;
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
    if (isRecording) {
        stopRecording(false);
    } else if (this.classList.contains('mic')) {
        startRecording();
    } else {
        sendMessage();
    }
});

$('discard-rec-btn').addEventListener('click', function () {
    stopRecording(true);
});

$('pause-rec-btn').addEventListener('click', function () {
    pauseRecording();
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

    const playBtn = e.target.closest('.vb-play-btn');
    if (playBtn) {
        const msgId = parseInt(playBtn.dataset.msgid, 10);
        playVoiceMessage(msgId);
        return;
    }

    const speedBtn = e.target.closest('.vb-speed-btn');
    if (speedBtn) {
        const msgId = parseInt(speedBtn.dataset.msgid, 10);
        togglePlaybackSpeed(msgId);
        return;
    }

    const waveformContainer = e.target.closest('.vb-waveform-container');
    if (waveformContainer) {
        const msgId = parseInt(waveformContainer.dataset.msgid, 10);
        const rect  = waveformContainer.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const pct   = Math.max(0, Math.min(100, (clickX / rect.width) * 100));
        seekVoiceMessage(msgId, pct);
        return;
    }
});

buildEmojiPicker();
renderMessages();

})();
</script>
