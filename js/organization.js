fetch("../php/fetch_organization.php")
.then(res => res.json())
.then(posts => {
  const container = document.getElementById("postsContainer");
  posts.forEach(post => {
    const div = document.createElement("div");
    div.className = "post";
    div.innerHTML = `
      <h4>${post.username}</h4>
      <p>${post.content}</p>
      <small>${new Date(post.created_at).toLocaleString()}</small>
    `;
    container.appendChild(div);
  });
});
