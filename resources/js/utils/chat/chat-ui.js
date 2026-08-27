window.AUTO_REPLIES = [
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

window.SAMPLE_PEAKS_IN = [
    0.25, 0.4, 0.7, 0.9, 0.6, 0.3, 0.8, 1.0, 0.65, 0.4, 0.8, 0.5, 0.3, 0.6, 0.9,
    0.7, 0.4, 0.2, 0.5, 0.8, 0.6, 0.3, 0.7, 0.5, 0.4, 0.2, 0.6, 0.3,
];
window.SAMPLE_PEAKS_OUT = [
    0.3, 0.6, 0.4, 0.8, 1.0, 0.7, 0.5, 0.3, 0.6, 0.9, 0.4, 0.2, 0.7, 0.8, 0.5,
    0.9, 0.6, 0.3, 0.5, 0.7, 0.4, 0.2, 0.6, 0.8, 0.5, 0.3, 0.4, 0.2,
];

window.DEMO_MESSAGES = [
    {
        out: false,
        text: "Hey! Are you free this evening? 👋",
        time: "9:10 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: true,
        text: "Yeah I should be! What's up?",
        time: "9:13 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: false,
        text: "We're planning a small get-together at Sarah's place. You should come! 🎉",
        time: "9:15 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: true,
        text: "Oh that sounds fun 😄 What time does it start?",
        time: "9:17 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: false,
        type: "voice",
        duration: 7,
        peaks: window.SAMPLE_PEAKS_IN,
        time: "9:18 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: true,
        text: "Got it! Thanks for sending the audio details 🎧",
        time: "9:20 AM",
        date: "Yesterday",
        status: "read",
    },
    {
        out: false,
        text: "Hey, did you end up watching that movie I recommended?",
        time: "11:45 AM",
        date: "Today",
        status: "read",
    },
    {
        out: true,
        type: "voice",
        duration: 5,
        peaks: window.SAMPLE_PEAKS_OUT,
        time: "11:52 AM",
        date: "Today",
        status: "read",
    },
    {
        out: false,
        text: "Can't wait to hear more! 🎉",
        time: "12:01 PM",
        date: "Today",
        status: "read",
    },
];

window.msgIdCounter = 400;

window.fmt = () =>
    new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

window.fmtDuration = function (s) {
    s = Math.round(s || 0);
    const m = Math.floor(s / 60);
    const sec = s % 60;
    return `${m}:${sec < 10 ? "0" : ""}${sec}`;
};

window.esc = function (t) {
    return (t || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\n/g, "<br>");
};

window.tickHTML = function (status) {
    if (status === "pending") {
        return `<span class="tick pending">
            <svg viewBox="0 0 16 11"><path d="M11.071.653l-6.383 7.17-2.75-2.75-1.5 1.5 4.25 4.25 7.883-8.669z"/></svg>
        </span>`;
    }
    if (status === "sent") {
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
};

window.generatePeaksHTML = function (peaks) {
    const p = peaks && peaks.length >= 10 ? peaks : window.SAMPLE_PEAKS_OUT;
    return p
        .map((val) => {
            const heightPct = Math.max(
                15,
                Math.min(100, Math.round(val * 100)),
            );
            return `<span class="vb-bar" style="height:${heightPct}%"></span>`;
        })
        .join("");
};

window.bubbleHTML = function (m) {
    if (m.type === "voice") {
        const durationStr = window.fmtDuration(m.duration || 5);
        return `
        <div class="msg-row ${m.out ? "out" : "in"}" data-msgid="${m.id}">
            <div class="bubble voice-bubble">
                <div class="vb-avatar">
                    ${
                        m.out
                            ? `<div class="vba-circle out" title="You">
                             <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M12 15c1.66 0 2.99-1.34 2.99-3L15 6c0-1.66-1.34-3-3-3S9 4.34 9 6v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 15 6.7 12H5c0 3.42 2.72 6.23 6 6.72V22h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
                             </svg>
                           </div>`
                            : `<div class="vba-circle in" title="${window.esc(window.brandName || "Betalogics")}">${
                                window.brandLogo
                                    ? `<img src="${window.brandLogo}" alt="${window.esc(window.brandName || "Betalogics")}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`
                                    : window.esc(window.brandInitials || "BE")
                            }</div>`
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
                                ${window.generatePeaksHTML(m.peaks)}
                            </div>
                            <div class="vb-waveform-progress" data-msgid="${m.id}" style="width: 0%"></div>
                            <div class="vb-waveform-handle" data-msgid="${m.id}" style="left: 0%"></div>
                        </div>
                    </div>
                    <div class="vb-footer">
                        <span class="vb-time-disp" data-msgid="${m.id}">${durationStr}</span>
                        <div class="vb-right-actions">
                            <button class="vb-download-btn" onclick="event.stopPropagation(); window.downloadAudioMessage(${m.id})" title="Download audio">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                </svg>
                            </button>
                            <button class="vb-speed-btn" data-msgid="${m.id}" title="Playback speed">1x</button>
                        </div>
                    </div>
                </div>
                <span class="msg-meta voice-meta">
                    <span class="msg-time">${m.time}</span>
                    ${m.out ? window.tickHTML(m.status) : ""}
                </span>
            </div>
        </div>`;
    }

    if (m.type === "document") {
        const fileUrl = m.fileUrl || m.url || "";
        const fileName = window.esc(m.fileName || "Document.pdf");
        const ext = (fileName.split('.').pop() || 'DOCUMENT').toUpperCase();
        return `
        <div class="msg-row ${m.out ? "out" : "in"}" data-msgid="${m.id}">
            <div class="bubble doc-bubble">
                <div class="doc-card" onclick="window.openInNewTab('${fileUrl}')" title="Open document in new tab">
                    <div class="doc-icon-box">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <div class="doc-info">
                        <div class="doc-name">${fileName}</div>
                        <div class="doc-subtext">${window.esc(m.fileSize || ext)}</div>
                    </div>
                    <div class="doc-actions">
                        <button type="button" class="doc-action-btn" onclick="event.stopPropagation(); window.openInNewTab('${fileUrl}')" title="Open document in new tab">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                            </svg>
                        </button>
                        <button type="button" class="doc-action-btn" onclick="event.stopPropagation(); window.downloadFile('${fileUrl}', '${fileName}')" title="Download document">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <span class="msg-meta" style="margin-top: 4px; padding: 0 4px 2px;">
                    <span class="msg-time">${m.time}</span>
                    ${m.out ? window.tickHTML(m.status) : ""}
                </span>
            </div>
        </div>`;
    }

    if (m.type === "photo") {
        const photoUrl = m.photoUrl || m.url || "";
        const fileName = window.esc(m.fileName || "image.jfif");
        return `
        <div class="msg-row ${m.out ? "out" : "in"}" data-msgid="${m.id}">
            <div class="bubble photo-bubble">
                <div class="photo-wrapper">
                    <img src="${photoUrl}" alt="Photo attachment" class="photo-preview" onclick="window.openInNewTab('${photoUrl}')" title="Click to view image in new tab">
                    <div class="photo-actions-overlay">
                        <button class="photo-action-btn" onclick="event.stopPropagation(); window.openInNewTab('${photoUrl}')" title="Open image in new tab">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                            </svg>
                        </button>
                        <button class="photo-action-btn" onclick="event.stopPropagation(); window.downloadFile('${photoUrl}', '${fileName}')" title="Download image">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <span class="msg-meta" style="margin-top: 4px; padding: 0 4px 2px;">
                    <span class="msg-time">${m.time}</span>
                    ${m.out ? window.tickHTML(m.status) : ""}
                </span>
            </div>
        </div>`;
    }

    return `
    <div class="msg-row ${m.out ? "out" : "in"}" data-msgid="${m.id}">
        <div class="bubble">
            <span class="msg-text">${window.esc(m.text)}</span>
            <span class="msg-meta">
                <span class="msg-time">${m.time}</span>
                ${m.out ? window.tickHTML(m.status) : ""}
            </span>
        </div>
    </div>`;
};

window.renderMessages = function () {
    const area = document.getElementById("messages-area");
    if (!area) return;
    area.innerHTML = "";
    let lastDate = null;

    window.DEMO_MESSAGES.forEach((m, i) => {
        m.id = i + 1;
        window.messageMetaData.set(m.id, {
            duration: m.duration || 5,
            speed: 1,
            peaks: m.peaks || window.SAMPLE_PEAKS_OUT,
        });
        if (m.date !== lastDate) {
            area.insertAdjacentHTML(
                "beforeend",
                `<div class="date-sep"><span>${m.date}</span></div>`,
            );
            lastDate = m.date;
        }
        area.insertAdjacentHTML("beforeend", window.bubbleHTML(m));
    });

    area.insertAdjacentHTML(
        "beforeend",
        `
        <div class="msg-row in" id="typing-row" style="display:none">
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>`,
    );

    window.scrollBottom();
};

window.scrollBottom = function () {
    const a = document.getElementById("messages-area");
    if (a) a.scrollTop = a.scrollHeight;
};

window.sendMessage = function () {
    const input = document.getElementById("msg-input");
    const text = input.value.trim();
    if (!text) return;

    window.msgIdCounter++;
    const m = {
        id: window.msgIdCounter,
        text,
        out: true,
        time: window.fmt(),
        status: "pending",
    };

    input.value = "";
    input.style.height = "";
    window.updateSendBtn();

    const area = document.getElementById("messages-area");
    const tyRow = document.getElementById("typing-row");
    const row = document.createElement("div");
    row.className = "msg-row out";
    row.dataset.msgid = m.id;
    row.innerHTML = window.bubbleHTML(m);
    area.insertBefore(row, tyRow);
    window.scrollBottom();

    setTimeout(() => window.updateTick(m.id, "sent"), 700);
    setTimeout(() => window.updateTick(m.id, "read"), 2000);

    setTimeout(() => window.showTyping(true), 2500);
    const delay = 4200 + Math.random() * 1000;
    setTimeout(() => {
        window.showTyping(false);
        window.fakeReply();
    }, delay);
};

window.updateTick = function (id, status) {
    const row = document.querySelector(`[data-msgid="${id}"]`);
    if (!row) return;
    const tick = row.querySelector(".tick");
    if (tick) tick.outerHTML = window.tickHTML(status);
};

window.showTyping = function (show) {
    const row = document.getElementById("typing-row");
    if (!row) return;
    row.style.display = show ? "flex" : "none";
    if (show) window.scrollBottom();
};

window.fakeReply = function () {
    window.msgIdCounter++;
    const text =
        window.AUTO_REPLIES[
            Math.floor(Math.random() * window.AUTO_REPLIES.length)
        ];
    const m = {
        id: window.msgIdCounter,
        text,
        out: false,
        time: window.fmt(),
        status: "read",
    };

    const area = document.getElementById("messages-area");
    const tyRow = document.getElementById("typing-row");
    const row = document.createElement("div");
    row.className = "msg-row in";
    row.dataset.msgid = m.id;
    row.innerHTML = window.bubbleHTML(m);
    area.insertBefore(row, tyRow);
    window.scrollBottom();
};

window.fakeVoiceReply = function () {
    window.msgIdCounter++;
    const newId = window.msgIdCounter;
    const dur = Math.floor(Math.random() * 6) + 3;

    window.audioBlobs.set(
        newId,
        URL.createObjectURL(window.createSyntheticAudioBlob(dur)),
    );
    window.messageMetaData.set(newId, {
        duration: dur,
        speed: 1,
        peaks: window.SAMPLE_PEAKS_IN,
    });

    const m = {
        id: newId,
        out: false,
        type: "voice",
        duration: dur,
        peaks: window.SAMPLE_PEAKS_IN,
        time: window.fmt(),
        status: "read",
    };

    const area = document.getElementById("messages-area");
    const tyRow = document.getElementById("typing-row");
    const row = document.createElement("div");
    row.className = "msg-row in";
    row.dataset.msgid = m.id;
    row.innerHTML = window.bubbleHTML(m);
    area.insertBefore(row, tyRow);
    window.scrollBottom();
};

window.autoResize = function (el) {
    el.style.height = "auto";
    el.style.height = Math.min(el.scrollHeight, 120) + "px";
};

window.updateSendBtn = function () {
    const btn = document.getElementById("send-btn");
    if (!btn) return;
    if (window.recState && window.recState.isRecording) return;
    if (document.getElementById("msg-input").value.trim())
        btn.classList.remove("mic");
    else btn.classList.add("mic");
};

window.sendAttachment = function (type, file) {
    if (!file) return;
    window.msgIdCounter++;
    const newMsgId = window.msgIdCounter;
    const time = window.fmt();

    const fileObjUrl = URL.createObjectURL(file);

    const m = {
        id: newMsgId,
        out: true,
        type: type,
        url: fileObjUrl,
        fileName: file.name,
        time: time,
        status: "pending",
    };

    if (type === "document") {
        m.fileUrl = fileObjUrl;
        m.fileSize = (file.size / (1024 * 1024)).toFixed(1) + " MB";
    } else if (type === "photo") {
        m.photoUrl = fileObjUrl;
    } else if (type === "audio") {
        m.type = "voice";
        m.audioUrl = fileObjUrl;
        m.duration = 10;
        window.audioBlobs.set(newMsgId, fileObjUrl);
        window.messageMetaData.set(newMsgId, {
            duration: 10,
            speed: 1,
            peaks: window.SAMPLE_PEAKS_OUT,
        });
    }

    const area = document.getElementById("messages-area");
    const tyRow = document.getElementById("typing-row");
    const row = document.createElement("div");
    row.className = "msg-row out";
    row.dataset.msgid = m.id;
    row.innerHTML = window.bubbleHTML(m);
    area.insertBefore(row, tyRow);
    window.scrollBottom();

    setTimeout(() => window.updateTick(m.id, "sent"), 700);
    setTimeout(() => window.updateTick(m.id, "read"), 2000);

    setTimeout(() => window.showTyping(true), 2500);
    const delay = 4000 + Math.random() * 1000;
    setTimeout(() => {
        window.showTyping(false);
        window.fakeReply();
    }, delay);
};
