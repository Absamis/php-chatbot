const CHAT_CARD_TOGGLE = document.querySelector("#chat-card-toggle");
const CHAT_CARD_TOGGLE_ICON = document.querySelector("#chat-card-toggle .icon");
const CHAT_CARD = document.querySelector("#chat-card");
const CHAT_CARD_BODY = document.querySelector("#chat-card-body");
const MSG_BOX = document.querySelector("#message-box");
const FILE_ATTACH = document.querySelector("#file-attach");
const MESSAGE_FORM = document.querySelector("#message-form");

const SERVER_BASE_URL = "http://localhost";

let messageQueue = [];
let messageStorage = {};
let timingDelay = null;

function dec2hex(dec) {
  return dec.toString(16).padStart(2, "0");
}

// generateId :: Integer -> String
function generateId(len) {
  var arr = new Uint8Array((len || 40) / 2);
  window.crypto.getRandomValues(arr);
  return Array.from(arr, dec2hex).join("");
}

function toggleChatCard() {
  if (CHAT_CARD.style.display == "none") CHAT_CARD.style.display = "flex";
  else CHAT_CARD.style.display = "none";

  if (CHAT_CARD_TOGGLE_ICON.textContent == "chat")
    CHAT_CARD_TOGGLE_ICON.textContent = "close";
  else CHAT_CARD_TOGGLE_ICON.textContent = "chat";
}

function resetForm() {
  CHAT_CARD_BODY.scrollTo(
    CHAT_CARD_BODY.scrollWidth,
    CHAT_CARD_BODY.scrollHeight
  );
//   FILE_ATTACH.value = null;
  if (MSG_BOX.style.border == "1px solid red")
    MSG_BOX.style.border = "1px solid #dae6fe";
  MSG_BOX.textContent = "";
}

function init() {
  CHAT_CARD.style.display = "none";
//   fetchDate();
//   fetchPreviousChat();
//   let cookie = getCookie("sender");
//   if (cookie === undefined) {
//     let sender = generateId(30);
//     setCookie("sender", sender, {
//       path: "/",
//         samesite: "strict",
//       expires: new Date(2147483647 * 1000)
//     });
//   }
    timingDelay = setInterval(getChatResponse, 30000);
  resetForm();
}

function fetchDate() {
  let prevdate = sessionStorage.getItem("chatDate");
  let date = Date.now();
  let dte = new Date(date);
  let mnth = dte.toString().split(" ")[1];
  let newdate = `${mnth} ${dte.getDate()}`;
  if (prevdate == null || prevdate != newdate) {
    let ndte = getDateNode(newdate);
    addMessageNode(ndte).then(() =>
      sessionStorage.setItem("chatdate", newdate)
    );
  }
}

function fetchPreviousChat() {
  let welcomeText = "Welcome to AbsamTech chatbot, please how may we help you?";
  let chats = sessionStorage.getItem("chatData");
  if (chats != null) {
    let prevChats = JSON.parse(chats);
    messageStorage = prevChats;
    let msgPromise = null;
    for (let item in prevChats) {
      if (prevChats[item].msgType == "message")
        msgPromise = getMessageNode(
          prevChats[item].msgText,
          item,
          prevChats[item].msgTime,
          prevChats[item].msgStatus
        );
      else if (prevChats[item].msgType == "response")
        msgPromise = getResponseNode(
          prevChats[item].msgText,
          item,
          prevChats[item].msgTime
        );
      msgPromise
        .then((node) => {
          return addMessageNode(node);
        })
        .then((node) => {
          return;
        });
    }
    timingDelay = setInterval(getChatResponse, 30000);
  } else {
    let msgPromise = getResponseNode(welcomeText);
    msgPromise
      .then((node) => {
        return addMessageNode(node);
      })
      .then((node) => {
        return;
      });
  }
}

function getResponseNode(text, newID = null, newTime = null) {
  return new Promise(function (resolve, reject) {
    let now = new Date();
    let nodeID = newID == null ? generateId(15) : newID;
    let para = document.createElement("div");
    para.className = "cht-message-block";
    para.id = nodeID;
    para.innerHTML = `
                    <span class="text-color">
                        <i class="material-icons" style="font-size: 30px">account_circle</i>
                    </span>`;
    let msg = document.createElement("div");
    msg.className = "response bg-gray-outline";
    let hour =
      now.getHours().toString().length > 1
        ? now.getHours()
        : "0" + now.getHours();
    let mins =
      now.getMinutes().toString().length > 1
        ? now.getMinutes()
        : "0" + now.getMinutes();
    let time = newTime == null ? `${hour}:${mins}` : newTime;
    msg.innerHTML = `
                <p class="mb-0 content text-left">${text}</p>
                <small class="d-block cht-time mt-0 text-right">
                    <span class="text-color">${time}</span>
                </small>
                `;
    para.appendChild(msg);
    if (newTime == null || (newTime != null && newID == null)) {
      messageStorage[nodeID] = {
        msgText: text,
        msgTime: time,
        msgType: "response",
      };
    }
    resolve(para);
  });
}

function getDateNode(date = null) {
  let sect = document.createElement("section");
  sect.innerHTML = `
                <div class="cht-date">
                    ${date}
                </div>`;
  return sect;
}

function updateMessageStatus(msgID, status) {
  let msg = document.querySelector("#msg-block");
  let childrenNode = msg.children;
  for (let elem of childrenNode) {
    if (elem.id == msgID) {
      let msgbx = document.getElementById(msgID).firstChild;
      let db = msgbx.children[1];
      db.children[1].textContent = status;
      messageStorage[msgID].msgStatus = status;
      sessionStorage.setItem("chatData", JSON.stringify(messageStorage));
      return;
    }
  }
}

function addMessageNode(msgNode) {
  return new Promise(function (resolve, reject) {
    let msg = document.querySelector("#msg-block");
    msg.append(msgNode);
    resolve(msgNode);
  });
}

function getMessageNode(text, newID = null, newTime = null, status = null) {
  return new Promise(function (resolve, reject) {
    let now = new Date();
    let nodeID = newID == null ? generateId(15) : newID;
    let para = document.createElement("div");
    para.className = "cht-message-block";
    para.id = nodeID;
    let msg = document.createElement("div");
    msg.className = "message bg-color-light";
    let hour =
      now.getHours().toString().length > 1
        ? now.getHours()
        : "0" + now.getHours();
    let mins =
      now.getMinutes().toString().length > 1
        ? now.getMinutes()
        : "0" + now.getMinutes();
    let time = newTime == null ? `${hour}:${mins}` : newTime;
    let state = status == null ? "query_builder" : status;
    msg.innerHTML = `
                <p class="mb-0 content text-left">${text}</p>
                <small class="d-block cht-time mt-0 text-right">
                    <span class="text-color">${time}</span>
                    <span class="state material-icons">${state}</span>
                </small>
                `;
    para.appendChild(msg);
    if (newTime == null) {
      messageStorage[nodeID] = {
        msgText: text,
        msgTime: `${hour}:${mins}`,
        msgStatus: "query_builder",
        msgType: "message",
      };
    }
    resolve(para);
  });
}

function validateFormInput() {
  if (MSG_BOX.textContent.trim() == "") {
    MSG_BOX.style.border = "1px solid red";
    return false;
  } else {
    MSG_BOX.style.border = "1px solid #dae6fe";
  }
  return true;
}

function getCookie(name) {
  let matches = document.cookie.match(
    new RegExp(
      "(?:^|; )" +
        name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
        "=([^;]*)"
    )
  );
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options = {}) {
  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString();
  }

  let updatedCookie =
    encodeURIComponent(name) + "=" + encodeURIComponent(value);

  for (let optionKey in options) {
    updatedCookie += "; " + optionKey;
    let optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }
  document.cookie = updatedCookie;
}

function destroyCookie(name) {
  setCookie(name, "", {
    "max-age": -1,
  });
}

function getChatResponse() {
  fetch(SERVER_BASE_URL + "/chatbotapp/users/chats/get-response.php", {
    method: "POST",
    headers: {},
  })
    .then((response) => {
      if (response.status == 200) return response.json();
      else
        return {
          responseCode: "99",
        };
    })
    .then((result) => {
    //   console.log(result);
      if (result.responseCode == "00") {
          let responseMsg = result.data;
        let response = JSON.parse(responseMsg.response);
          if (responseMsg.status == "closed"){
              clearInterval(timingDelay);
              timingDelay = null;
          }
        for (let elem of response) {

          let msgPromise = getResponseNode(elem.message, null, elem.time);
          msgPromise
            .then((node) => {
              return addMessageNode(node);
            })
            .then((node) => {
              sessionStorage.setItem(
                "chatData",
                JSON.stringify(messageStorage)
              );
            });
        }
      } 
    });
  // .catch((err) => {
  //     //console.log(err);
  // });
}

MSG_BOX.addEventListener("keypress", function (event) {
    if (event.code == "Enter")
        event.preventDefault();
  if (MSG_BOX.style.border == "1px solid red")
    MSG_BOX.style.border = "1px solid #c0d3f8";
  if (this.textContent.length >= 150) {
    event.preventDefault();
  }
});
MESSAGE_FORM.addEventListener("submit", function (event) {
  event.preventDefault();
  if (!validateFormInput()) {
    return;
  }
    // console.log(MSG_BOX.textContent);
    // return;
  let msgPromise = getMessageNode(MSG_BOX.textContent.trim());
  msgPromise
    .then((node) => {
      messageQueue.push(node.id);
      return addMessageNode(node);
    })
    .then((node) => {
      if (timingDelay == null) {
        timingDelay = setInterval(getChatResponse, 30000);
      }
      sessionStorage.setItem("chatData", JSON.stringify(messageStorage));
      updateMessageStatus(node.id, "done");
      let formData = new FormData();
      formData.append("messageid", node.id);
      formData.append("message", MSG_BOX.textContent.trim());
      fetch(SERVER_BASE_URL + "/chatbotapp/users/chats/send-message.php", {
        method: "POST",
        headers: {},
        body: formData,
      })
        .then((response) => {
          return response.json();
        })
        .then((result) => {
          //console.log(result);
          let data = result.data;
          if (result.responseCode == "00") {
            updateMessageStatus(data.messageID, "done_all");
          }
        });
      // .catch((err) => {
      //     //console.log(err);
      // });
      resetForm();
    });
});
CHAT_CARD_TOGGLE.addEventListener("click", function () {
  resetForm();
  toggleChatCard();
});
window.onload = function () {
  init();
};
