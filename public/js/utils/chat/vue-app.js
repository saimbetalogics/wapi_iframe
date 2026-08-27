(function () {
  "use strict";

  const DEFAULT_IDENTITY =
    "eyJpdiI6InY1Z2pIZ21neENrcTZ5NExMbXpDU3c9PSIsInZhbHVlIjoicFR2Rk4rdG1rZE1rSjkrQjMwaFI3TXlvSFg4RmFsVWFGQ3IyYyt1cTg5OHhTQ3Zwa2REUEZGdmxTb3JiaDFqT0ZsUHFvNHdBWXlzdjBGMUQ2L25IYXc1cTErNlc5RzBUSmtYMnlLMUIvUll1L2ZuV0Fac2R2MklYTWJlTk1qM04iLCJtYWMiOiJkNzg3ZTE4OWFhNTNiMGFiNTRjNTZjYzc3ZWYxNGI3ZThhNzVmMDA2NTQ4MjA5ZjQxMjViMzZhNTJkOGZkY2IzIiwidGFnIjoiIn0=";

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof Vue === "undefined") {
      console.warn("Vue.js is not loaded.");
      return;
    }

    window.chatApp = new Vue({
      el: "#wapi-chat",
      data: {
        brandName: "Betalogics",
        brandLogo: null,
        inputText: "",
        attachMenuOpen: false,
        isRecording: false,
        isPausedRecording: false,
        recTimerText: "0:00",
        isTyping: false,
        messages: [],
        currentPage: 1,
        hasMore: true,
        isLoadingMore: false,
        initialLoading: true,
        scrollCooldown: false,
        perPage: 50,
      },
      computed: {
        brandInitials: function () {
          if (!this.brandName) return "B";
          const parts = this.brandName.trim().split(/\s+/);
          if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
          }
          return parts[0].slice(0, 2).toUpperCase();
        },
      },
      mounted: function () {
        this.fetchInitialMessages();
        this.bindGlobalClick();
      },
      methods: {
        updateBrandFromResponse: function (responseData) {
          if (!responseData) return;
          let name = "";
          let logo = null;
          if (responseData.brand) {
            if (typeof responseData.brand === "object" && responseData.brand !== null) {
              name = responseData.brand.name || responseData.brand.title || "";
              logo = responseData.brand.logo || responseData.brand.avatar || responseData.brand.image || responseData.brand.icon || null;
            } else if (typeof responseData.brand === "string") {
              name = responseData.brand;
            }
          }
          if (!name && responseData.name) {
            if (typeof responseData.name === "string") {
              name = responseData.name;
            }
          }
          if (name) {
            this.brandName = name;
            window.brandName = name;
            window.brandInitials = this.brandInitials;
          }
          if (logo) {
            this.brandLogo = logo;
            window.brandLogo = logo;
          }
        },
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
            DEFAULT_IDENTITY
          );
        },
        handleScroll: function (e) {
          const el = e.target;
          if (
            el.scrollTop <= 50 &&
            this.hasMore &&
            !this.isLoadingMore &&
            !this.initialLoading &&
            !this.scrollCooldown
          ) {
            this.fetchMoreMessages();
          }
        },
        fetchInitialMessages: function () {
          const self = this;
          self.initialLoading = true;
          const identity = self.getIdentity();

          const url = `https://wapi.betalogics.com/e-web/get-messages?identity=${encodeURIComponent(
            identity
          )}&page=1&per_page=${self.perPage}`;

          axios
            .get(url, {
              headers: { Accept: "application/json" },
            })
            .then(function (response) {
              if (response.data) {
                self.updateBrandFromResponse(response.data);
              }
              if (
                response.data &&
                response.data.success &&
                response.data.data
              ) {
                const rawItems = response.data.data;
                const formatted = rawItems
                  .map((item) => self.mapApiMessage(item))
                  .reverse();
                self.messages = formatted;

                if (response.data.pagination) {
                  self.currentPage = response.data.pagination.current_page || 1;
                  self.hasMore = !!response.data.pagination.has_more;
                } else {
                  self.hasMore = false;
                }

                self.messages.forEach((m) => {
                  window.messageMetaData.set(m.id, {
                    duration: m.duration || 5,
                    speed: 1,
                    peaks: m.peaks || window.SAMPLE_PEAKS_OUT,
                  });
                });
                self.scrollToBottom();
              }
            })
            .catch(function (error) {
              console.error("Error fetching initial messages:", error);
            })
            .finally(function () {
              self.initialLoading = false;
            });
        },
        fetchMoreMessages: function () {
          const self = this;
          if (self.isLoadingMore || !self.hasMore || self.scrollCooldown)
            return;
          self.isLoadingMore = true;
          self.scrollCooldown = true;

          const area = document.getElementById("messages-area");
          let anchorRow = null;
          let anchorOffset = 0;

          if (area) {
            const rows = area.querySelectorAll(".msg-row");
            if (rows.length > 0) {
              anchorRow = rows[0];
              anchorOffset = anchorRow.getBoundingClientRect().top;
            }
          }

          const nextPage = self.currentPage + 1;
          const identity = self.getIdentity();
          const url = `https://wapi.betalogics.com/e-web/get-messages?identity=${encodeURIComponent(
            identity
          )}&page=${nextPage}&per_page=${self.perPage}`;

          axios
            .get(url, {
              headers: { Accept: "application/json" },
            })
            .then(function (response) {
              if (response.data) {
                self.updateBrandFromResponse(response.data);
              }
              if (
                response.data &&
                response.data.success &&
                response.data.data
              ) {
                const rawItems = response.data.data;
                const formatted = rawItems
                  .map((item) => self.mapApiMessage(item))
                  .reverse();

                self.messages = [...formatted, ...self.messages];

                if (response.data.pagination) {
                  self.currentPage =
                    response.data.pagination.current_page || nextPage;
                  self.hasMore = !!response.data.pagination.has_more;
                } else {
                  self.hasMore = false;
                }

                formatted.forEach((m) => {
                  window.messageMetaData.set(m.id, {
                    duration: m.duration || 5,
                    speed: 1,
                    peaks: m.peaks || window.SAMPLE_PEAKS_OUT,
                  });
                });

                self.$nextTick(() => {
                  requestAnimationFrame(() => {
                    if (area && anchorRow) {
                      const newOffset = anchorRow.getBoundingClientRect().top;
                      area.scrollTop += newOffset - anchorOffset;
                    }
                    setTimeout(() => {
                      self.scrollCooldown = false;
                    }, 250);
                  });
                });
              } else {
                self.scrollCooldown = false;
              }
            })
            .catch(function (error) {
              console.error("Error fetching pagination messages:", error);
              self.scrollCooldown = false;
            })
            .finally(function () {
              self.isLoadingMore = false;
            });
        },
        mapApiMessage: function (item) {
          const isOut = item.direction === 1;
          const bodyVal =
            item.content && item.content.body && item.content.body.value
              ? item.content.body.value
              : "";
          const createdAt =
            item.content && item.content.body && item.content.body.created_at
              ? item.content.body.created_at
              : null;

          let timeStr = new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
          });
          if (createdAt) {
            const d = new Date(createdAt);
            if (!isNaN(d.getTime())) {
              timeStr = d.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
              });
            }
          }

          const media =
            item.content && item.content.header && item.content.header.media
              ? item.content.header.media
              : null;
          const mediaType =
            media && media.type ? media.type.toUpperCase() : null;
          const mediaUrl = media && media.url ? media.url : null;

          let msgType = "text";
          let photoUrl = "";
          let fileName = "";
          let fileSize = "";

          if (mediaType === "IMAGE" || mediaType === "PHOTO") {
            msgType = "photo";
            photoUrl = mediaUrl;
          } else if (mediaType === "DOCUMENT" || mediaType === "FILE") {
            msgType = "document";
            fileName = mediaUrl ? mediaUrl.split("/").pop() : "Document";
            fileSize = "";
          } else if (mediaType === "AUDIO" || mediaType === "VOICE") {
            msgType = "voice";
          }

          const statusMap = {
            sending: "pending",
            sent: "sent",
            read: "read",
            delivered: "sent",
          };
          const status = statusMap[item.status] || (isOut ? "sent" : "read");

          return {
            id:
              (item.message && item.message.message_id) ||
              Math.random() + Date.now(),
            out: isOut,
            type: msgType,
            text: bodyVal,
            photoUrl: photoUrl,
            fileName: fileName,
            fileSize: fileSize,
            duration: 5,
            peaks: window.SAMPLE_PEAKS_OUT,
            time: timeStr,
            status: status,
          };
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
                identity: identity,
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
          formData.append("identity", identity);
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
          formData.append("identity", identity);
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
