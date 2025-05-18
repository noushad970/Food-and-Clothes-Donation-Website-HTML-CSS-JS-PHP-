function toggleEdit() {
    const section = document.getElementById("editSection");
    section.style.display = section.style.display === "none" ? "block" : "none";
  }
  fetch("../php/fetch_user_posts.php")
      .then(res => res.json())
      .then(posts => {
        const container = document.getElementById("userPosts");
        posts.forEach(post => {
          const div = document.createElement("div");
          div.className = "post";
          div.innerHTML = `
            <h4>${post.username}</h4>
            <p>${post.content}</p>
            <small>${new Date(post.created_at).toLocaleString()}</small>
            <form method="POST" action="../php/delete_post.php">
              <input type="hidden" name="post_id" value="${post.id}" />
              <button type="submit">Delete</button>
            </form>
          `;
          container.appendChild(div);
        });
      });