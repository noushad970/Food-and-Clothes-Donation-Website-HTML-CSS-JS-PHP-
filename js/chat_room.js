let currentChatUser = null;

document.addEventListener('DOMContentLoaded', () => {
    loadChatList();

    document.getElementById('sendBtn').addEventListener('click', () => {
        const msgInput = document.getElementById('messageInput');
        const message = msgInput.value.trim();

        if (currentChatUser && message !== '') {
            fetch('../php/new/send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: currentChatUser, message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    msgInput.value = '';
                    loadMessages(currentChatUser);
                }
            });
        }
    });
});

function loadChatList() {
    fetch('../php/new/fetch_chat_list.php')
        .then(res => res.json())
        .then(users => {
            const list = document.getElementById('chatUsers');
            list.innerHTML = '';

            users.forEach(user => {
                const li = document.createElement('li');
                li.textContent = user.username;
                li.addEventListener('click', () => {
                    currentChatUser = user.id;
                    document.getElementById('chatHeader').innerHTML = `<h3>Chatting with ${user.username}</h3>`;
                    loadMessages(user.id);
                });
                list.appendChild(li);
            });
        });
}

function loadMessages(receiverId) {
    fetch(`../php/new/fetch_messages.php?receiver_id=${receiverId}`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById('chatMessages');
            box.innerHTML = '';

            data.forEach(msg => {
                const div = document.createElement('div');
                div.className = msg.is_sender ? 'chat-message sender' : 'chat-message receiver';
                div.textContent = msg.message;
                box.appendChild(div);
            });

            box.scrollTop = box.scrollHeight;
        });
}
