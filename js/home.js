// Fetch user info from PHP session
fetch('../php/get_user.php')
  .then(response => response.json())
  .then(data => {
    const nav = document.getElementById("navButtons");
    const userSpan = document.getElementById("username");

    if (data.loggedIn) {
      userSpan.textContent = data.username;

      const logoutBtn = document.createElement("button");
      logoutBtn.textContent = "Logout";
      logoutBtn.onclick = () => {
        window.location.href = "../php/logout.php";
      };
      nav.appendChild(logoutBtn);
    } else {
      userSpan.textContent = "Guest";

      const loginBtn = document.createElement("button");
      loginBtn.textContent = "Login";
      loginBtn.onclick = () => {
        window.location.href = "login.html";
      };

      const signupBtn = document.createElement("button");
      signupBtn.textContent = "Signup";
      signupBtn.onclick = () => {
        window.location.href = "signup.html";
      };

      nav.appendChild(loginBtn);
      nav.appendChild(signupBtn);
    }
  });

  
  
  fetch("../php/fetch_posts.php")
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

     
