document.getElementById('usersearch').addEventListener('submit', function(e) {
    e.preventDefault();

    const container = document.getElementById('id04');
    if (!container || container.offsetParent === null) {
        // id04 does not exist or is not visible, do nothing
        return;
    }

    const formData = new FormData(this);

    fetch('../../routes/allpost.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(posts => {
        container.innerHTML = '';

        if (posts.length === 0) {
            container.innerHTML = '<p>User did not post any picture.</p>';
            return;
        }

        posts.forEach(post => {
            const card = document.createElement('div');
            card.classList.add('post-card');

            card.innerHTML = `
                <h3>${post.title}</h3>
                <p><strong>By:</strong> ${post.name} ${post.surname}</p>
                <p><strong>Date:</strong> ${new Date(post.post_date).toLocaleDateString()}</p>
                <img src="${post.postimage}" alt="${post.title}" style="max-width: 300px; height: auto; display: block; margin-bottom: 8px;">
                <button class="delete-button" data-post-id="${post.post_id}" data-image-id="${post.image_id}">
                    Delete Image
                </button><br>
                <b>-------------------------------------------------------------------</b><br>
            `;

            container.appendChild(card);
        });

        // Add delete button listeners
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const imageId = this.getAttribute('data-image-id');

                if (!confirm('Are you sure you want to delete this picture?')) return;

                fetch(`../../routes/deletepostpicture.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `post_id=${encodeURIComponent(postId)}&image_id=${encodeURIComponent(imageId)}`
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    // Optionally, re-trigger the fetch:
                    document.getElementById('usersearch').dispatchEvent(new Event('submit'));
                })
                .catch(err => {
                    console.error(err);
                    alert('Error deleting image');
                });
            });
        });
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = '<p>Error loading posts.</p>';
    });
});
