/**
 * Audio Player Utility Module for Wapi Chat
 * Handles HTML5 audio playback, waveform progress updates, audio seeking,
 * playback rate toggles, and synthetic audio generation for fallbacks.
 */

window.audioBlobs = new Map();
window.messageMetaData = new Map();
window.activePlayer = null;

window.createSyntheticAudioBlob = function (durationSec = 4) {
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
};

window.getAudioUrl = function (msgId, duration = 5) {
    if (window.audioBlobs.has(msgId)) {
        const item = window.audioBlobs.get(msgId);
        return typeof item === 'string' ? item : URL.createObjectURL(item);
    }
    const blob = window.createSyntheticAudioBlob(duration);
    const url  = URL.createObjectURL(blob);
    window.audioBlobs.set(msgId, url);
    return url;
};

window.stopActivePlayer = function () {
    if (!window.activePlayer) return;
    try {
        window.activePlayer.audio.pause();
    } catch(e) {}
    
    const oldId = window.activePlayer.msgId;
    window.activePlayer = null;
    
    window.updatePlayerUI(oldId, 0, false);
};

window.updatePlayerUI = function (msgId, progressPct, isPlaying) {
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

    const meta = window.messageMetaData.get(msgId) || { duration: 5 };
    const timeDisp = row.querySelector('.vb-time-disp');
    if (timeDisp) {
        if (isPlaying && window.activePlayer && window.activePlayer.msgId === msgId) {
            const curTime = window.activePlayer.audio.currentTime;
            timeDisp.textContent = `${window.fmtDuration(curTime)} / ${window.fmtDuration(meta.duration)}`;
        } else {
            timeDisp.textContent = window.fmtDuration(meta.duration);
        }
    }
};

window.playVoiceMessage = function (msgId) {
    const meta = window.messageMetaData.get(msgId) || { duration: 5, speed: 1 };
    
    if (window.activePlayer && window.activePlayer.msgId === msgId) {
        if (window.activePlayer.isPlaying) {
            window.activePlayer.audio.pause();
            window.activePlayer.isPlaying = false;
            window.updatePlayerUI(msgId, (window.activePlayer.audio.currentTime / (window.activePlayer.audio.duration || meta.duration)) * 100, false);
        } else {
            window.activePlayer.audio.play();
            window.activePlayer.isPlaying = true;
            window.updatePlayerUI(msgId, (window.activePlayer.audio.currentTime / (window.activePlayer.audio.duration || meta.duration)) * 100, true);
        }
        return;
    }

    if (window.activePlayer) {
        window.stopActivePlayer();
    }

    const url = window.getAudioUrl(msgId, meta.duration);
    const audio = new Audio(url);
    audio.playbackRate = meta.speed || 1;

    window.activePlayer = {
        msgId,
        audio,
        speed: meta.speed || 1,
        isPlaying: true
    };

    audio.addEventListener('timeupdate', () => {
        if (!window.activePlayer || window.activePlayer.msgId !== msgId) return;
        const dur = audio.duration || meta.duration;
        const pct = (audio.currentTime / dur) * 100;
        window.updatePlayerUI(msgId, pct, true);
    });

    audio.addEventListener('ended', () => {
        if (window.activePlayer && window.activePlayer.msgId === msgId) {
            window.activePlayer = null;
        }
        window.updatePlayerUI(msgId, 0, false);
    });

    audio.play().then(() => {
        window.updatePlayerUI(msgId, 0, true);
    }).catch(err => {
        console.warn('Audio play fallback:', err);
        window.updatePlayerUI(msgId, 0, false);
    });
};

window.seekVoiceMessage = function (msgId, clickPct) {
    const meta = window.messageMetaData.get(msgId) || { duration: 5 };
    if (!window.activePlayer || window.activePlayer.msgId !== msgId) {
        window.playVoiceMessage(msgId);
    }
    if (window.activePlayer && window.activePlayer.msgId === msgId) {
        const dur = window.activePlayer.audio.duration || meta.duration;
        window.activePlayer.audio.currentTime = (clickPct / 100) * dur;
        window.updatePlayerUI(msgId, clickPct, window.activePlayer.isPlaying);
    }
};

window.togglePlaybackSpeed = function (msgId) {
    const meta = window.messageMetaData.get(msgId) || { duration: 5, speed: 1 };
    const speeds = [1, 1.5, 2];
    const curIdx = speeds.indexOf(meta.speed || 1);
    const nextSpeed = speeds[(curIdx + 1) % speeds.length];
    meta.speed = nextSpeed;
    window.messageMetaData.set(msgId, meta);

    const btn = document.querySelector(`.msg-row[data-msgid="${msgId}"] .vb-speed-btn`);
    if (btn) btn.textContent = `${nextSpeed}x`;

    if (window.activePlayer && window.activePlayer.msgId === msgId) {
        window.activePlayer.audio.playbackRate = nextSpeed;
        window.activePlayer.speed = nextSpeed;
    }
};
