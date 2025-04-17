$(document).ready(function(){
    // Handle login form submission
    $('#logmodal').submit(function(e){
        e.preventDefault();  // Prevent the default form submission

        // Prepare data for the request
        let email = $('#LEmail').val();
        let password = $('#LPSW').val();

        // Use jQuery's form serialization for URL-encoded format
        let data = {
            email: email,
            password: password
        };

        // Make the AJAX request
        $.ajax({
            type: 'POST',
            url: 'login.php',  // Pointing to a PHP file
            data: data,        // Sending as URL-encoded key-value pairs
            success: function(response){
                try {
                    let res = JSON.parse(response);  // Parse JSON string returned by PHP

                    if (res.success) {
                        openChat();  // Call a function on successful login
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    alert('Invalid server response.');
                }
            },
            error: function(){
                alert('Login failed. Please try again.');
            }
        });
    });
});
