(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        const $ = (id) => document.getElementById(id);

        const msgInput = $("msg-input");
        if (msgInput) {
            msgInput.addEventListener("input", function () {
                window.autoResize(this);
                window.updateSendBtn();
            });

            msgInput.addEventListener("keydown", function (e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    window.sendMessage();
                }
            });
        }

        const sendBtn = $("send-btn");
        if (sendBtn) {
            sendBtn.addEventListener("click", function () {
                if (window.recState && window.recState.isRecording) {
                    window.stopRecording(false);
                } else if (this.classList.contains("mic")) {
                    window.startRecording();
                } else {
                    window.sendMessage();
                }
            });
        }

        const discardBtn = $("discard-rec-btn");
        if (discardBtn) {
            discardBtn.addEventListener("click", function () {
                window.stopRecording(true);
            });
        }

        const pauseBtn = $("pause-rec-btn");
        if (pauseBtn) {
            pauseBtn.addEventListener("click", function () {
                window.pauseRecording();
            });
        }

        document.addEventListener("click", function (e) {
            const playBtn = e.target.closest(".vb-play-btn");
            if (playBtn) {
                const msgId = parseInt(playBtn.dataset.msgid, 10);
                window.playVoiceMessage(msgId);
                return;
            }

            const speedBtn = e.target.closest(".vb-speed-btn");
            if (speedBtn) {
                const msgId = parseInt(speedBtn.dataset.msgid, 10);
                window.togglePlaybackSpeed(msgId);
                return;
            }

            const waveformContainer = e.target.closest(
                ".vb-waveform-container",
            );
            if (waveformContainer) {
                const msgId = parseInt(waveformContainer.dataset.msgid, 10);
                const rect = waveformContainer.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const pct = Math.max(
                    0,
                    Math.min(100, (clickX / rect.width) * 100),
                );
                window.seekVoiceMessage(msgId, pct);
                return;
            }
        });

        window.renderMessages();
    });
})();
