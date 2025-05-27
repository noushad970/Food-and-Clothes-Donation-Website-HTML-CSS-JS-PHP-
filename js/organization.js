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

let currentPage = 1;
let currentSearch = "";

function fetchOrganizations(page = 1, search = "") {
  fetch(`../php/organization.php?page=${page}&search=${encodeURIComponent(search)}`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('orgList');
      if (page === 1) list.innerHTML = '';

      if (data.length === 0) {
        if (page === 1) {
          list.innerHTML = "<p>No organizations found.</p>";
        } else {
          alert("No more organizations.");
          currentPage--;
        }
        return;
      }

      data.forEach(org => {
        const div = document.createElement('div');
        div.className = "org-card";
        
        div.innerHTML = `
          <div class="org-image-container">
            <img src="../uploads/${org.org_image}" alt="${org.org_name}">
          </div>
          <div class="org-info">
            <h3>${org.org_name}</h3>
            <p><strong>Address:</strong> ${org.org_location}</p>
            <p><strong>Phone:</strong> ${org.org_phone}</p>
          </div>
          <div class="message-box">
            <input type="text" class="messageInput" placeholder="Type your message..." data-org-id="${org.user_id}">
            <button class="sendMessageBtn" data-org-id="${org.user_id}">Send</button>
          </div>
          <div class="org-rating-section" data-org-id="${org.user_id}">
            <div class="stars">
              <span class="star" data-value="1">★</span>
              <span class="star" data-value="2">★</span>
              <span class="star" data-value="3">★</span>
              <span class="star" data-value="4">★</span>
              <span class="star" data-value="5">★</span>
            </div>
            <button class="give-rate-btn">Give Rate</button>
            <div class="rating-info">
            Average Rating: <span class="avg-rating">${org.avg_rating || 0}</span> / 5 |
            Total Ratings: <span class="total-ratings">${org.total_ratings || 0}</span>
            </div>

          </div>
        `;

        list.appendChild(div);
      });
      setupStarRating();
    });
}

function searchOrganizations() {
  currentSearch = document.getElementById("searchInput").value;
  currentPage = 1;
  fetchOrganizations(currentPage, currentSearch);
}

function loadNextPage() {
  currentPage++;
  fetchOrganizations(currentPage, currentSearch);
}

function loadPreviousPage() {
  if (currentPage > 1) {
    currentPage--;
    fetchOrganizations(currentPage, currentSearch);
  }
}
function setupStarRating() {
  document.querySelectorAll('.org-rating-section').forEach(section => {
    let selectedRating = 0;
    const stars = section.querySelectorAll('.star');
    const orgId = section.getAttribute('data-org-id');

    stars.forEach(star => {
      star.addEventListener('mouseover', () => {
        const value = parseInt(star.getAttribute('data-value'));
        stars.forEach(s => {
          s.classList.toggle('hover', parseInt(s.getAttribute('data-value')) <= value);
        });
      });

      star.addEventListener('mouseout', () => {
        stars.forEach(s => s.classList.remove('hover'));
      });

      star.addEventListener('click', () => {
        selectedRating = parseInt(star.getAttribute('data-value'));
        stars.forEach(s => {
          s.classList.toggle('selected', parseInt(s.getAttribute('data-value')) <= selectedRating);
        });
      });
    });

    section.querySelector('.give-rate-btn').addEventListener('click', () => {
      if (selectedRating === 0) {
        alert('Please select a stars rating first!');
        return;
      }

      fetch('../php/submit_rating.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ org_id: orgId, rating: selectedRating })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          section.querySelector('.avg-rating').innerText = data.avg_rating.toFixed(1);
          section.querySelector('.total-ratings').innerText = data.rating_count;
          alert('Thank you for your rating!');
          selectedRating = 0;
          stars.forEach(s => s.classList.remove('selected'));
        } else {
          alert(data.message || 'Failed to submit rating.');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error submitting rating.');
      });
    });
  });
}


fetch('../php/get_user_data.php')
  .then(res => res.json())
  .then(data => {
    const pic = data.profile_picture ? `../uploads/${data.profile_picture}` : '../assets/default.jpg';
    document.getElementById('profilePic').src = pic;
  });

fetchOrganizations();
