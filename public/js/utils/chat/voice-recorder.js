window.recState = {
  isRecording: false,
  isPausedRecording: false,
  mediaRecorder: null,
  recordedChunks: [],
  recElapsedMs: 0,
  recTimerInterval: null,
  audioContext: null,
  analyserNode: null,
  animFrameId: null,
  recordedPeaks: [],
};

window.startRecording = function () {
  const s = window.recState;
  s.isRecording = true;
  s.isPausedRecording = false;
  s.recordedChunks = [];
  s.recordedPeaks = [];
  s.recElapsedMs = 0;

  const $ = (id) => document.getElementById(id);
  $("normal-input-wrap").style.display = "none";
  $("voice-rec-bar").style.display = "flex";
  $("send-btn").classList.remove("mic");
  $("send-btn").classList.add("recording");
  $("rec-timer").textContent = "0:00";

  const pauseBtn = $("pause-rec-btn");
  pauseBtn.querySelector(".pause-icon").style.display = "block";
  pauseBtn.querySelector(".resume-icon").style.display = "none";

  clearInterval(s.recTimerInterval);
  s.recTimerInterval = setInterval(() => {
    if (!s.isPausedRecording) {
      s.recElapsedMs += 200;
      $("rec-timer").textContent = window.fmtDuration(s.recElapsedMs / 1000);
    }
  }, 200);

  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices
      .getUserMedia({ audio: true })
      .then((stream) => {
        try {
          s.mediaRecorder = new MediaRecorder(stream);
          s.mediaRecorder.ondataavailable = (e) => {
            if (e.data.size > 0) s.recordedChunks.push(e.data);
          };
          s.mediaRecorder.start(100);

          s.audioContext = new (window.AudioContext ||
            window.webkitAudioContext)();
          const source = s.audioContext.createMediaStreamSource(stream);
          s.analyserNode = s.audioContext.createAnalyser();
          s.analyserNode.fftSize = 64;
          source.connect(s.analyserNode);

          window.drawVisualizer();
        } catch (err) {
          window.drawSyntheticVisualizer();
        }
      })
      .catch((err) => {
        console.warn(
          "Microphone permission denied/unavailable. Using synthetic visualizer:",
          err
        );
        window.drawSyntheticVisualizer();
      });
  } else {
    window.drawSyntheticVisualizer();
  }
};

window.drawVisualizer = function () {
  const s = window.recState;
  const canvas = document.getElementById("rec-visualizer");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");
  const dataArray = new Uint8Array(s.analyserNode.frequencyBinCount);

  function renderFrame() {
    if (!s.isRecording) return;
    s.animFrameId = requestAnimationFrame(renderFrame);

    if (!s.isPausedRecording && s.analyserNode) {
      s.analyserNode.getByteFrequencyData(dataArray);
      let sum = 0;
      for (let i = 0; i < dataArray.length; i++) sum += dataArray[i];
      const avg = sum / (dataArray.length * 255);
      if (Math.random() < 0.3) s.recordedPeaks.push(Math.max(0.2, avg));
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    const barWidth = 3;
    const gap = 2;
    const numBars = Math.floor(canvas.width / (barWidth + gap));

    for (let i = 0; i < numBars; i++) {
      const val = dataArray[i % dataArray.length] / 255;
      const h = s.isPausedRecording ? 4 : Math.max(3, val * canvas.height);
      const x = i * (barWidth + gap);
      const y = (canvas.height - h) / 2;

      ctx.fillStyle = "#00a884";
      ctx.beginPath();
      ctx.roundRect
        ? ctx.roundRect(x, y, barWidth, h, 2)
        : ctx.fillRect(x, y, barWidth, h);
      ctx.fill();
    }
  }
  renderFrame();
};

window.drawSyntheticVisualizer = function () {
  const s = window.recState;
  const canvas = document.getElementById("rec-visualizer");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  function renderFrame() {
    if (!s.isRecording) return;
    s.animFrameId = requestAnimationFrame(renderFrame);

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    const barWidth = 3;
    const gap = 2;
    const numBars = Math.floor(canvas.width / (barWidth + gap));
    const time = Date.now() / 150;

    for (let i = 0; i < numBars; i++) {
      const val = (Math.sin(time + i * 0.4) + 1) / 2;
      const h = s.isPausedRecording
        ? 4
        : Math.max(3, val * canvas.height * 0.85);
      const x = i * (barWidth + gap);
      const y = (canvas.height - h) / 2;

      if (!s.isPausedRecording && i % 3 === 0 && Math.random() < 0.2) {
        s.recordedPeaks.push(Math.max(0.25, val));
      }

      ctx.fillStyle = "#00a884";
      ctx.beginPath();
      ctx.roundRect
        ? ctx.roundRect(x, y, barWidth, h, 2)
        : ctx.fillRect(x, y, barWidth, h);
      ctx.fill();
    }
  }
  renderFrame();
};

window.pauseRecording = function () {
  const s = window.recState;
  if (!s.isRecording) return;
  s.isPausedRecording = !s.isPausedRecording;
  const pauseBtn = document.getElementById("pause-rec-btn");
  if (s.isPausedRecording) {
    pauseBtn.querySelector(".pause-icon").style.display = "none";
    pauseBtn.querySelector(".resume-icon").style.display = "block";
    if (s.mediaRecorder && s.mediaRecorder.state === "recording")
      s.mediaRecorder.pause();
  } else {
    pauseBtn.querySelector(".pause-icon").style.display = "block";
    pauseBtn.querySelector(".resume-icon").style.display = "none";
    if (s.mediaRecorder && s.mediaRecorder.state === "paused")
      s.mediaRecorder.resume();
  }
};

window.stopRecording = function (discard = false) {
  const s = window.recState;
  if (!s.isRecording) return;
  s.isRecording = false;
  s.isPausedRecording = false;

  clearInterval(s.recTimerInterval);
  if (s.animFrameId) cancelAnimationFrame(s.animFrameId);
  if (s.audioContext) {
    try {
      s.audioContext.close();
    } catch (e) {}
  }

  const durationSec = Math.max(1, Math.round(s.recElapsedMs / 1000));

  if (s.mediaRecorder && s.mediaRecorder.state !== "inactive") {
    try {
      s.mediaRecorder.stop();
    } catch (e) {}
  }

  const $ = (id) => document.getElementById(id);
  $("voice-rec-bar").style.display = "none";
  $("normal-input-wrap").style.display = "flex";
  $("send-btn").classList.remove("recording");
  window.updateSendBtn();

  if (discard) {
    s.recordedChunks = [];
    s.recordedPeaks = [];
    return;
  }

  let blob = null;
  if (s.recordedChunks.length > 0) {
    blob = new Blob(s.recordedChunks, {
      type: s.mediaRecorder.mimeType || "audio/webm",
    });
  } else {
    blob = window.createSyntheticAudioBlob(durationSec);
  }

  let peaks = s.recordedPeaks.slice(-28);
  while (peaks.length < 28) {
    peaks.push(Math.min(1, Math.max(0.2, Math.random())));
  }

  if (window.chatApp && window.chatApp.sendVoiceApi) {
    window.chatApp.sendVoiceApi(durationSec, peaks, blob);
    return;
  }

  window.msgIdCounter++;
  const newMsgId = window.msgIdCounter;
  window.audioBlobs.set(newMsgId, URL.createObjectURL(blob));
  window.messageMetaData.set(newMsgId, {
    duration: durationSec,
    speed: 1,
    peaks,
  });

  const m = {
    id: newMsgId,
    out: true,
    type: "voice",
    duration: durationSec,
    peaks,
    time: window.fmt(),
    status: "pending",
  };

  const area = $("messages-area");
  const tyRow = $("typing-row");
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
    window.fakeVoiceReply();
  }, delay);
};
