// This script uses the globally available firebase and window.auth objects

// Add an event listener to the "Send Verification Email" button
document.getElementById("send-email-btn").addEventListener("click", function() {
    var email = document.getElementById("email").value;

    // Define the action code settings for the email link
    var actionCodeSettings = {
        url: 'http://localhost/rsdsl/home.html', // make sure is not the rsdsl2
        handleCodeInApp: true
    };

    // Send the sign-in link to the provided email address using Firebase Auth
    window.auth.sendSignInLinkToEmail(email, actionCodeSettings)
        .then(function() {
            // Save the email in local storage for later use during sign-in
            window.localStorage.setItem('emailForSignIn', email);
            alert("Verification email sent!");
        })
        .catch(function(error) {
            console.error("Error sending verification email:", error);
            alert("Error sending verification email: " + error.message);
        });
});
