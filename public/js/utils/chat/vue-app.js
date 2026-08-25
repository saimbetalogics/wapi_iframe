(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof Vue === "undefined") {
      console.warn("Vue.js is not loaded.");
      return;
    }

    window.chatApp = new Vue({
      el: "#wapi-chat",
      data: {
        inputText: "",
        attachMenuOpen: false,
        isRecording: false,
        isPausedRecording: false,
        recTimerText: "0:00",
        isTyping: false,
        messages: [
          {
            id: 1,
            out: false,
            text: "Hey! Are you free this evening? 👋",
            time: "9:10 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 2,
            out: true,
            text: "Yeah I should be! What's up?",
            time: "9:13 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 3,
            out: false,
            text: "We're planning a small get-together at Sarah's place. You should come! 🎉",
            time: "9:15 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 4,
            out: true,
            text: "Oh that sounds fun 😄 What time does it start?",
            time: "9:17 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 5,
            out: false,
            type: "voice",
            duration: 7,
            peaks: window.SAMPLE_PEAKS_IN,
            time: "9:18 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 6,
            out: true,
            text: "Got it! Thanks for sending the audio details 🎧",
            time: "9:20 AM",
            date: "Yesterday",
            status: "read",
          },
          {
            id: 7,
            out: false,
            text: "Hey, did you end up watching that movie I recommended?",
            time: "11:45 AM",
            date: "Today",
            status: "read",
          },
          {
            id: 8,
            out: true,
            type: "voice",
            duration: 5,
            peaks: window.SAMPLE_PEAKS_OUT,
            time: "11:52 AM",
            date: "Today",
            status: "read",
          },
          {
            id: 9,
            out: false,
            text: "Can't wait to hear more! 🎉",
            time: "12:01 PM",
            date: "Today",
            status: "read",
          },
        ],
      },
      mounted: function () {
        this.messages.forEach((m) => {
          window.messageMetaData.set(m.id, {
            duration: m.duration || 5,
            speed: 1,
            peaks: m.peaks || window.SAMPLE_PEAKS_OUT,
          });
        });
        this.scrollToBottom();
        this.bindGlobalClick();
      },
      methods: {
        scrollToBottom: function () {
          this.$nextTick(() => {
            const area = document.getElementById("messages-area");
            if (area) area.scrollTop = area.scrollHeight;
          });
        },
        autoResizeInput: function (e) {
          const el = e.target;
          el.style.height = "auto";
          el.style.height = Math.min(el.scrollHeight, 120) + "px";
        },
        toggleAttachMenu: function () {
          this.attachMenuOpen = !this.attachMenuOpen;
        },
        closeAttachMenu: function () {
          this.attachMenuOpen = false;
        },
        triggerFileInput: function (type) {
          this.closeAttachMenu();
          const el = document.getElementById("file-input-" + type);
          if (el) el.click();
        },
        handleFileChange: function (type, event) {
          const file = event.target.files && event.target.files[0];
          if (file) {
            this.sendAttachmentApi(type, file);
            event.target.value = "";
          }
        },
        getIdentity: function () {
          return (
            window.WAPI_IDENTITY ||
            new URLSearchParams(window.location.search).get("identity") ||
            ""
          );
        },
        sendMessageApi: function () {
          const text = this.inputText.trim();
          if (!text) return;

          this.inputText = "";
          const textarea = document.getElementById("msg-input");
          if (textarea) textarea.style.height = "";

          const self = this;
          const identity = this.getIdentity();

          const msgId = Date.now();
          const outMsg = {
            id: msgId,
            text: text,
            out: true,
            time: new Date().toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            }),
            status: "pending",
          };

          self.messages.push(outMsg);
          self.scrollToBottom();

          axios
            .post(
              "https://wapi.betalogics.com/e-web/send-message",
              {
                identity:
                  "eyJpdiI6InY1Z2pIZ21neENrcTZ5NExMbXpDU3c9PSIsInZhbHVlIjoicFR2Rk4rdG1rZE1rSjkrQjMwaFI3TXlvSFg4RmFsVWFGQ3IyYyt1cTg5OHhTQ3Zwa2REUEZGdmxTb3JiaDFqT0ZsUHFvNHdBWXlzdjBGMUQ2L25IYXc1cTErNlc5RzBUSmtYMnlLMUIvUll1L2ZuV0Fac2R2MklYTWJlTk1qM04iLCJtYWMiOiJkNzg3ZTE4OWFhNTNiMGFiNTRjNTZjYzc3ZWYxNGI3ZThhNzVmMDA2NTQ4MjA5ZjQxMjViMzZhNTJkOGZkY2IzIiwidGFnIjoiIn0=",
                type: "TEXT",
                value: text,
              },
              {
                headers: {
                  "Content-Type": "application/json",
                  Accept: "application/json",
                },
              }
            )
            .then(function (response) {
              outMsg.status = "sent";
              setTimeout(() => {
                outMsg.status = "read";
              }, 1000);

              if (response.data && response.data.reply) {
                setTimeout(() => {
                  self.messages.push(response.data.reply);
                  self.scrollToBottom();
                }, 1200);
              }
            })
            .catch(function (error) {
              console.error("WAPI Text Send Error:", error);
              outMsg.status = "sent";
            });
        },
        sendVoiceApi: function (durationSec, peaks, blob) {
          const self = this;
          const identity = this.getIdentity();
          const msgId = Date.now();

          const outMsg = {
            id: msgId,
            type: "voice",
            duration: durationSec,
            peaks: peaks,
            out: true,
            time: new Date().toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            }),
            status: "pending",
          };

          if (blob) {
            window.audioBlobs.set(msgId, URL.createObjectURL(blob));
          }
          window.messageMetaData.set(msgId, {
            duration: durationSec,
            speed: 1,
            peaks,
          });

          self.messages.push(outMsg);
          self.scrollToBottom();

          const formData = new FormData();
          formData.append(
            "identity",
            "eyJpdiI6InY1Z2pIZ21neENrcTZ5NExMbXpDU3c9PSIsInZhbHVlIjoicFR2Rk4rdG1rZE1rSjkrQjMwaFI3TXlvSFg4RmFsVWFGQ3IyYyt1cTg5OHhTQ3Zwa2REUEZGdmxTb3JiaDFqT0ZsUHFvNHdBWXlzdjBGMUQ2L25IYXc1cTErNlc5RzBUSmtYMnlLMUIvUll1L2ZuV0Fac2R2MklYTWJlTk1qM04iLCJtYWMiOiJkNzg3ZTE4OWFhNTNiMGFiNTRjNTZjYzc3ZWYxNGI3ZThhNzVmMDA2NTQ4MjA5ZjQxMjViMzZhNTJkOGZkY2IzIiwidGFnIjoiIn0="
          );
          formData.append("type", "AUDIO");
          if (blob) {
            formData.append("value", blob, "recording.webm");
          }

          axios
            .post("https://wapi.betalogics.com/e-web/send-message", formData, {
              headers: {
                Accept: "application/json",
              },
            })
            .then(function (response) {
              outMsg.status = "sent";
              setTimeout(() => {
                outMsg.status = "read";
              }, 1000);
            })
            .catch(function (error) {
              console.error("WAPI Voice Send Error:", error);
              outMsg.status = "sent";
            });
        },
        sendAttachmentApi: function (type, file) {
          const self = this;
          const identity = this.getIdentity();
          const msgId = Date.now();

          let apiType = "DOCUMENT";
          if (type === "photo") apiType = "IMAGE";
          else if (type === "audio") apiType = "AUDIO";

          const outMsg = {
            id: msgId,
            type: type,
            out: true,
            time: new Date().toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            }),
            status: "pending",
          };

          if (type === "photo") {
            outMsg.photoUrl = URL.createObjectURL(file);
          } else if (type === "document" || type === "doc") {
            outMsg.type = "document";
            outMsg.fileName = file.name;
            outMsg.fileSize = (file.size / (1024 * 1024)).toFixed(1) + " MB";
          } else if (type === "audio") {
            outMsg.type = "voice";
            outMsg.duration = 10;
            window.audioBlobs.set(msgId, URL.createObjectURL(file));
            window.messageMetaData.set(msgId, {
              duration: 10,
              speed: 1,
              peaks: window.SAMPLE_PEAKS_OUT,
            });
          }

          self.messages.push(outMsg);
          self.scrollToBottom();

          const formData = new FormData();
          formData.append(
            "identity",
            "eyJpdiI6InY1Z2pIZ21neENrcTZ5NExMbXpDU3c9PSIsInZhbHVlIjoicFR2Rk4rdG1rZE1rSjkrQjMwaFI3TXlvSFg4RmFsVWFGQ3IyYyt1cTg5OHhTQ3Zwa2REUEZGdmxTb3JiaDFqT0ZsUHFvNHdBWXlzdjBGMUQ2L25IYXc1cTErNlc5RzBUSmtYMnlLMUIvUll1L2ZuV0Fac2R2MklYTWJlTk1qM04iLCJtYWMiOiJkNzg3ZTE4OWFhNTNiMGFiNTRjNTZjYzc3ZWYxNGI3ZThhNzVmMDA2NTQ4MjA5ZjQxMjViMzZhNTJkOGZkY2IzIiwidGFnIjoiIn0="
          );
          formData.append("type", apiType);
          formData.append("value", file);

          axios
            .post("https://wapi.betalogics.com/e-web/send-message", formData, {
              headers: {
                Accept: "application/json",
              },
            })
            .then(function (response) {
              outMsg.status = "sent";
              setTimeout(() => {
                outMsg.status = "read";
              }, 1000);
            })
            .catch(function (error) {
              console.error("WAPI Attachment Send Error:", error);
              outMsg.status = "sent";
            });
        },
        playVoice: function (msgId) {
          window.playVoiceMessage(msgId);
        },
        toggleSpeed: function (msgId) {
          window.togglePlaybackSpeed(msgId);
        },
        seekVoice: function (msgId, event) {
          const container = event.currentTarget;
          const rect = container.getBoundingClientRect();
          const clickX = event.clientX - rect.left;
          const pct = Math.max(0, Math.min(100, (clickX / rect.width) * 100));
          window.seekVoiceMessage(msgId, pct);
        },
        startRec: function () {
          window.startRecording();
          this.isRecording = true;
        },
        pauseRec: function () {
          window.pauseRecording();
        },
        stopRec: function (discard) {
          window.stopRecording(discard);
          this.isRecording = false;
        },
        esc: function (t) {
          return window.esc ? window.esc(t) : t || "";
        },
        fmtDuration: function (s) {
          return window.fmtDuration ? window.fmtDuration(s) : s;
        },
        tickHTML: function (status) {
          return window.tickHTML ? window.tickHTML(status) : "";
        },
        generatePeaksHTML: function (peaks) {
          return window.generatePeaksHTML
            ? window.generatePeaksHTML(peaks)
            : "";
        },
        bindGlobalClick: function () {
          const self = this;
          document.addEventListener("click", function (e) {
            const menu = document.getElementById("attach-menu");
            const attachBtn = document.getElementById("attach-btn");
            if (
              menu &&
              attachBtn &&
              !menu.contains(e.target) &&
              !attachBtn.contains(e.target)
            ) {
              self.attachMenuOpen = false;
            }
          });
        },
      },
    });
  });
})();
