function checkAge() {
    const dobInput = document.getElementById('RDOB').value;
    if (!dobInput) return;  // No date selected yet

    const dob = new Date(dobInput);
    const today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    const dayDiff = today.getDate() - dob.getDate();

    // Adjust age if birthday hasn't occurred yet this year
    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
      age--;
    }

    if (age < 13) {
      alert("Sorry, you must be at least 13 years old to create an account.");
      document.getElementById('RDOB').value = '';
    }
}

document.getElementById('regmodal').addEventListener('submit', function(e) {
  e.preventDefault(); // This stops the browser from submitting the form normally

  const formData = new FormData(this);

  fetch('./routes/regAuth.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if(data.success){
      alert(data.message);
      this.reset();
	  showLogin();
      // close modal or redirect as you want here, but no page reload or url change happens
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => {
    alert('Error occurred');
    console.error(err);
  });
});
