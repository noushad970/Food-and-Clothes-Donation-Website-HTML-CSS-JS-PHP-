// Check for unread messages every 10 seconds
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

// Toggle popup open/close
function toggleChatPopup() {
  const popup = document.getElementById("chatPopup");
  if (popup.style.display === "flex") {
      popup.style.display = "none";
  } else {
      popup.style.display = "flex";
      loadChatList();
  }
}
document.addEventListener("DOMContentLoaded", function() {
  toggleChatPopup();
});
// Load the list of users from backend
function loadChatList() {
  const userListDiv = document.getElementById("userList");
  userListDiv.innerHTML = "Loading users...";
  fetch("../php/fetch_chat_users.php")
      .then(res => res.json())
      .then(data => {
          userListDiv.innerHTML = "";
          data.forEach(user => {
              const btn = document.createElement("button");
              btn.className = "chat-user-btn";
              btn.textContent = user.username;
              btn.onclick = () => openChatWith(user.id, user.username);
              userListDiv.appendChild(btn);
          });
      });
}

// Open chat with a selected user
function openChatWith(receiverId, receiverName = "User") {
  currentReceiverId = receiverId;
  document.getElementById("chatTitle").innerText = `Chat with ${receiverName}`;
  document.querySelector(".chat-input-area").style.display = "flex";
  loadMessages(receiverId);
}




// Send message to the selected receiver
function sendMessage() {
  const input = document.getElementById("chatInput");
  const msg = input.value.trim();
  if (!msg || !currentReceiverId) return;
  
  fetch("../php/send_message.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
          receiver_id: currentReceiverId,
          message: msg
      })
  })
  .then(res => res.json())
  .then(data => {
      if (data.success) {
          input.value = "";
          loadMessages(currentReceiverId);

      }
  });
}

