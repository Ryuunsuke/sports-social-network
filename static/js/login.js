document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('logmodal');
    
    if (!form) {
        console.error('Login form not found.');
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch('./routes/logAuth.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                window.location.href = './templates/dashboard.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(e => {
            console.error('Fetch or JSON parse error:', e);
            alert('Server returned invalid response.');
        })
        .finally(() => {
            if (submitBtn) submitBtn.disabled = false;
        });
    });
});
