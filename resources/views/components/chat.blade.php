<div id="wapi-chat">
    <div id="chat-header">
        <div class="h-avatar" style="background:#e91e63">
            <img v-if="brandLogo" :src="brandLogo" :alt="brandName" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            <span v-else>@{{ brandInitials }}</span>
        </div>
        <div class="h-info">
            <div class="h-name">@{{ brandName }}</div>
        </div>
        <div class="h-icons">
            <button class="icon-btn" title="More options">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="messages-area" @scroll="handleScroll">
        <div v-if="isLoadingMore" class="top-loader-wrap">
            <div class="top-loader-spinner"></div>
        </div>
        <template v-for="m in messages">
            <div :key="m.id" :class="['msg-row', m.out ? 'out' : 'in']" :data-msgid="m.id">
                <div v-if="m.type === 'voice'" class="bubble voice-bubble">
                    <div class="vb-avatar">
                        <div v-if="m.out" class="vba-circle out" title="You">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M12 15c1.66 0 2.99-1.34 2.99-3L15 6c0-1.66-1.34-3-3-3S9 4.34 9 6v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 15 6.7 12H5c0 3.42 2.72 6.23 6 6.72V22h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
                            </svg>
                        </div>
                        <div v-else class="vba-circle in" :title="brandName">
                            <img v-if="brandLogo" :src="brandLogo" :alt="brandName" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                            <span v-else>@{{ brandInitials }}</span>
                        </div>
                    </div>
                    <div class="vb-content">
                        <div class="vb-main">
                            <button class="vb-play-btn" :data-msgid="m.id" @click="playVoice(m.id)" title="Play voice message">
                                <svg class="play-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <svg class="pause-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor" style="display:none">
                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                </svg>
                            </button>
                            <div class="vb-waveform-container" :data-msgid="m.id" @click="seekVoice(m.id, $event)" title="Seek audio">
                                <div class="vb-waveform-bars" :data-msgid="m.id" v-html="generatePeaksHTML(m.peaks)"></div>
                                <div class="vb-waveform-progress" :data-msgid="m.id" style="width: 0%"></div>
                                <div class="vb-waveform-handle" :data-msgid="m.id" style="left: 0%"></div>
                            </div>
                        </div>
                        <div class="vb-footer">
                            <span class="vb-time-disp" :data-msgid="m.id">@{{ fmtDuration(m.duration) }}</span>
                            <div class="vb-right-actions">
                                <button class="vb-download-btn" @click.stop="downloadAudio(m)" title="Download audio">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                    </svg>
                                </button>
                                <button class="vb-speed-btn" :data-msgid="m.id" @click.stop="toggleSpeed(m.id)" title="Playback speed">1x</button>
                            </div>
                        </div>
                    </div>
                    <span class="msg-meta voice-meta">
                        <span class="msg-time">@{{ m.time }}</span>
                        <span v-if="m.out" v-html="tickHTML(m.status)"></span>
                    </span>
                </div>

                <div v-else-if="m.type === 'document'" class="bubble doc-bubble">
                    <div class="doc-card" @click="openInNewTab(m.fileUrl || m.url)" title="Open document in new tab">
                        <div class="doc-icon-box">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                            </svg>
                        </div>
                        <div class="doc-info">
                            <div class="doc-name">@{{ m.fileName || 'Document.pdf' }}</div>
                            <div class="doc-subtext">@{{ m.fileSize || getDocExt(m.fileName) }}</div>
                        </div>
                        <div class="doc-actions">
                            <button type="button" class="doc-action-btn" @click.stop="openInNewTab(m.fileUrl || m.url)" title="Open document in new tab">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                                </svg>
                            </button>
                            <button type="button" class="doc-action-btn" @click.stop="downloadFile(m.fileUrl || m.url, m.fileName || 'Document.pdf')" title="Download document">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <span class="msg-meta" style="margin-top: 4px; padding: 0 4px 2px;">
                        <span class="msg-time">@{{ m.time }}</span>
                        <span v-if="m.out" v-html="tickHTML(m.status)"></span>
                    </span>
                </div>

                <div v-else-if="m.type === 'photo'" class="bubble photo-bubble">
                    <div class="photo-wrapper">
                        <img :src="m.photoUrl || m.url" alt="Photo attachment" class="photo-preview" @click="openInNewTab(m.photoUrl || m.url)" title="Click to view image in new tab">
                        <div class="photo-actions-overlay">
                            <button type="button" class="photo-action-btn" @click.stop="openInNewTab(m.photoUrl || m.url)" title="Open image in new tab">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                                </svg>
                            </button>
                            <button type="button" class="photo-action-btn" @click.stop="downloadFile(m.photoUrl || m.url, m.fileName || 'image.jfif')" title="Download image">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <span class="msg-meta" style="margin-top: 4px; padding: 0 4px 2px;">
                        <span class="msg-time">@{{ m.time }}</span>
                        <span v-if="m.out" v-html="tickHTML(m.status)"></span>
                    </span>
                </div>

                <div v-else class="bubble">
                    <span class="msg-text" v-html="esc(m.text)"></span>
                    <span class="msg-meta">
                        <span class="msg-time">@{{ m.time }}</span>
                        <span v-if="m.out" v-html="tickHTML(m.status)"></span>
                    </span>
                </div>
            </div>
        </template>

        <div v-if="isTyping" class="msg-row in" id="typing-row">
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>

    <div id="attach-menu" :class="{ open: attachMenuOpen }">
        <button class="attach-opt" id="opt-doc" @click="triggerFileInput('doc')" title="Document">
            <span class="attach-icon-circle doc">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>
            </span>
            <span class="attach-label">Document</span>
        </button>
        <button class="attach-opt" id="opt-photo" @click="triggerFileInput('photo')" title="Photo">
            <span class="attach-icon-circle photo">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
            </span>
            <span class="attach-label">Photo</span>
        </button>
        <button class="attach-opt" id="opt-audio" @click="triggerFileInput('audio')" title="Audio">
            <span class="attach-icon-circle audio">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </span>
            <span class="attach-label">Audio</span>
        </button>
    </div>

    <input type="file" id="file-input-doc" accept=".pdf,.doc,.docx,.txt,.zip,.xlsx,.pptx" @change="handleFileChange('doc', $event)" style="display:none">
    <input type="file" id="file-input-photo" accept="image/*" @change="handleFileChange('photo', $event)" style="display:none">
    <input type="file" id="file-input-audio" accept="audio/*" @change="handleFileChange('audio', $event)" style="display:none">

    <div id="chat-input-bar">
        <div id="normal-input-wrap">
            <button class="input-icon-btn" id="attach-btn" @click.stop="toggleAttachMenu" title="Attach file">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M1.816 15.556v.001c0 1.502.584 2.912 1.646 3.972s2.472 1.647 3.974 1.647a5.58 5.58 0 0 0 3.972-1.645l9.547-9.548c.769-.768 1.147-1.767 1.058-2.817-.079-.968-.548-1.927-1.319-2.698-1.594-1.592-4.068-1.711-5.517-.262l-7.916 7.915c-.881.881-.792 2.25.214 3.261.959.958 2.423 1.053 3.263.215l5.511-5.512c.28-.28.267-.722.053-.936l-.244-.244c-.191-.191-.567-.349-.957.04l-5.506 5.506c-.18.18-.635.127-.976-.214-.098-.097-.576-.613-.213-.973l7.915-7.917c.818-.817 2.267-.699 3.23.262.5.501.802 1.1.849 1.685.051.573-.156 1.111-.589 1.543l-9.547 9.549a3.97 3.97 0 0 1-2.829 1.171 3.975 3.975 0 0 1-2.83-1.173 3.973 3.973 0 0 1-1.172-2.828c0-1.071.415-2.076 1.172-2.83l7.209-7.211c.157-.157.264-.579.028-.814L11.5 4.36a.572.572 0 0 0-.834.018L3.458 11.79a5.567 5.567 0 0 0-1.642 3.766z"/>
                </svg>
            </button>
            <div id="msg-input-wrap">
                <textarea id="msg-input" v-model="inputText" @input="autoResizeInput" @keydown.enter.exact.prevent="sendMessageApi" placeholder="Type a message" rows="1" autocomplete="off"></textarea>
            </div>
        </div>

        <div id="voice-rec-bar">
            <button class="input-icon-btn danger" id="discard-rec-btn" @click="stopRec(true)" title="Discard recording">
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
            <button class="input-icon-btn" id="pause-rec-btn" @click="pauseRec" title="Pause / Resume recording">
                <svg class="pause-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
                <svg class="resume-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor" style="display:none">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
        </div>

        <button id="send-btn" :class="{ mic: !inputText.trim() && !isRecording }" @click="isRecording ? stopRec(false) : (!inputText.trim() ? startRec() : sendMessageApi())">
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
<script src="{{ asset('js/utils/chat/vue-app.js') }}"></script>
