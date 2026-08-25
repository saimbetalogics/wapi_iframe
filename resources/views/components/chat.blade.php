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

    <div id="chat-input-bar">
        <div id="normal-input-wrap">
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

<script src="{{ asset('js/utils/chat/audio-player.js') }}"></script>
<script src="{{ asset('js/utils/chat/voice-recorder.js') }}"></script>
<script src="{{ asset('js/utils/chat/chat-ui.js') }}"></script>
<script src="{{ asset('js/utils/chat/app.js') }}"></script>
