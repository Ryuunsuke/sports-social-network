document.getElementById('usersearch').addEventListener('submit', function(e) {
    e.preventDefault();

    const resultsDiv = document.getElementById('id03');

    // Check if #id03 is visible (not display:none, and has size)
    const isVisible = resultsDiv.offsetParent !== null;

    if (!isVisible) {
        // If not visible, do nothing (or optionally alert)
        return;
    }

    const formData = new FormData(this);

    fetch('../../routes/alluser.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(users => {
        const results = document.getElementById('id03');
        results.innerHTML = '';

        if (users.length === 0) {
            results.innerHTML = '<p>No users found.</p>';
            return;
        }

        users.forEach(user => {
            const card = document.createElement('div');
            card.classList.add('user-card');

            const isUnsubscribed = user.unsubscribe_date !== null;

            card.innerHTML = `
                <img src="${user.profilepic}" alt="${user.name}" />
                <div class="user-info">
                    <p><strong>${user.name} ${user.surname}</strong></p>
                    <button 
                        class="action-button" 
                        data-user-id="${user.id}" 
                        data-action="${user.unsubscribe_date === null ? 'unsubscribe' : 'subscribe'}">
                        ${user.unsubscribe_date === null ? 'Unsubscribe' : 'Subscribe'}
                    </button>
                </div>
            `;
            results.appendChild(card);
        });

        // Add button event listeners
        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const action = this.getAttribute('data-action');

                const target = action === 'unsubscribe' ? 'deregister.php' : 'resubscribe.php';

                fetch(`../../routes/${target}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(userId)}`
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    document.getElementById('usersearch').dispatchEvent(new Event('submit'));
                })
                .catch(err => {
                    console.error(err);
                    alert('Operation failed.');
                });
            });
        });
    });
});