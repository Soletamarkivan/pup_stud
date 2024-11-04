// Function to fetch and display user data
async function loadUserProfile() {
    try {
        // Replace `user_ID` with the actual user ID from the session (if stored server-side)
        const response = await fetch(`admin-profile.php?user_ID=56`); // Adjust URL as needed
        const data = await response.json();

        if (data) {
            // Populate the form fields with the user data
            document.getElementById("first_name").value = data.first_name || '';
            document.getElementById("last_name").value = data.last_name || '';
            document.getElementById("middle_name").value = data.middle_name || '';
            document.getElementById("user_role").value = data.user_role || '';
            document.getElementById("user_email").value = data.user_email || '';
            document.getElementById("contact_num").value = data.contact_num || '';
            document.getElementById("username").value = data.username || '';
        } else {
            console.error("No data received");
        }
    } catch (error) {
        console.error("Error fetching profile data:", error);
    }
}

// Function to update the profile data
async function updateProfile(event) {
    event.preventDefault();

    const userData = {
        user_ID: 56, // Replace with dynamic user_ID as required
        first_name: document.getElementById("first_name").value,
        last_name: document.getElementById("last_name").value,
        middle_name: document.getElementById("middle_name").value,
        user_role: document.getElementById("user_role").value,
        user_email: document.getElementById("user_email").value,
        contact_num: document.getElementById("contact_num").value,
        username: document.getElementById("username").value,
        user_password: document.getElementById("user_password").value
    };

    try {
        const response = await fetch("/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/admin-clerk/admin_profile.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(userData)
        });

        const result = await response.json();

        if (result.success) {
            alert("Profile updated successfully.");
        } else {
            alert("Failed to update profile.");
        }
    } catch (error) {
        console.error("Error updating profile:", error);
    }
}

// Load profile data when the page loads
window.onload = loadUserProfile;

document.getElementById('change-photo-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent form submission
    document.getElementById('user_idpic_upload').click(); // Trigger the file input
});
