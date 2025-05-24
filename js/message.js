
function checkUnread() {
    fetch("../php/check_unread.php")
      .then(res => res.json())
      .then(data => {
        document.getElementById("messageDot").style.display = data.unread > 0 ? "inline-block" : "none";
      });
  }
  checkUnread();
  setInterval(checkUnread, 10000);
  
let currentReceiverId = null;

function toggleChatPopup() {
  const popup = document.getElementById("chatPopup");
  if (popup.style.display === "flex") {
    popup.style.display = "none";
  } else {
    popup.style.display = "flex";
    loadChatList();
  }
}

function openChatWith(receiverId, receiverName = "User") {
  currentReceiverId = receiverId;
  document.getElementById("chatTitle").innerText = `Chat with ${receiverName}`;
  document.querySelector(".chat-input-area").style.display = "flex";
  loadMessages(receiverId);
  console.log('clicked on openwithchat');
  
}

function sendMessage() {
  const msg = document.getElementById("chatInput").value.trim();
  if (!msg || !currentReceiverId) return;

  fetch("../php/send_message.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      receiver_id: currentReceiverId,
      message: msg
    })
  }).then(res => res.json()).then(data => {
    if (data.success) {
      document.getElementById("chatInput").value = "";
      loadMessages(currentReceiverId);
    }
  });
}

function loadMessages(receiverId) {
  fetch("../php/fetch_messages.php?receiver_id=" + receiverId)
    .then(res => res.json())
    .then(data => {
      const box = document.getElementById("chatMessages");
      box.innerHTML = "";
      data.forEach(msg => {
        const div = document.createElement("div");
        div.className = msg.is_sender ? "chat-message sender" : "chat-message receiver";
        div.innerText = msg.message;
        box.appendChild(div);
      });
      box.scrollTop = box.scrollHeight;
    });
}

function loadChatList() {
  document.getElementById("chatMessages").innerHTML = "Loading chats...";
  fetch("../php/fetch_chat_users.php")
    .then(res => res.json())
    .then(data => {
      const box = document.getElementById("chatMessages");
      box.innerHTML = "";
      data.forEach(user => {
        const div = document.createElement("div");
        div.className = "chat-user";
        div.innerText = user.username;
        div.onclick = () => openChatWith(user.id, user.username);
        box.appendChild(div);
      });
    });
}







