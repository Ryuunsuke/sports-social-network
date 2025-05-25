const form = document.getElementById('logmodal');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch('./routes/logAuth.php', {
    method: 'POST',
    body: formData
    })
    .then(response => response.text())  // get raw text first
    .then(text => {
    try {
        const data = JSON.parse(text);
        if(data.success){
        alert(data.message);
        this.reset();
        window.location.href = './templates/dashboard.php';
        } else {
        alert('Error: ' + data.message);
        }
    } catch (e) {
        console.error('Failed to parse JSON:', e);
        console.log('Raw response:', text);
        alert('Server returned invalid response. Check console.');
    }
    });
});
