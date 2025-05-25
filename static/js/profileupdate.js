document.getElementById('profilemodal').addEventListener('submit', function(e) {
  e.preventDefault(); // This stops the browser from submitting the form normally

  const formData = new FormData(this);

  fetch('../../routes/updateprofile.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if(data.success){
      alert(data.message);
        window.location.href = 'profile.php';
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => {
    alert('Error occurred');
    console.error(err);
  });
});