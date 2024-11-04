// Fetch user data and populate the table
function loadUsers() {
    fetch('admin-list.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('roles-data');
            tableBody.innerHTML = '';

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
            } else {
                data.forEach(user => {
                    const tableRow = `
                    <tr>
                        <td>${user.user_role}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.contact}</td>
                        <td>
                            <button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="editUser(${user.user_ID})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" title="Delete" onclick="confirmDelete(${user.user_ID})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    tableBody.innerHTML += tableRow;
                });
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}


function editUser(user_ID) {
    fetch(`admin-list.php?id=${user_ID}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById("editUserID").value = user_ID;
                document.getElementById("editUserRole").value = data.user_role; // Set the role dropdown
                document.getElementById("editUserName").value = data.name;
                document.getElementById("editUserEmail").value = data.email;
                document.getElementById("editUserContact").value = data.contact;

                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            } else {
                console.error('No data found for the user ID:', user_ID);
            }
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
        });
}


// Confirm deletion of a user
let userIdToDelete; // Variable to store the ID of the user to delete

function confirmDelete(user_ID) {
    userIdToDelete = user_ID; // Store the ID of the user to delete
    const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show();
}

// Handle the confirm delete button click
document.getElementById("confirmDelete").addEventListener("click", function() {
    // Make AJAX call to delete the user
    fetch(`admin-list.php`, {
        method: 'DELETE',
        body: new URLSearchParams({ id: userIdToDelete }) // Use userIdToDelete here
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide delete confirmation modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            deleteModal.hide();

            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Reload users after a brief delay
            setTimeout(() => {
                loadUsers(); // Reload users
            }, 1500);
        } else {
            alert('Error deleting user: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting user:', error);
    });
});




// Event listener for edit form submission
document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);

    fetch('admin-list.php', { // Assuming you're merging edit user functionality into admin-list.php
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            loadUsers(); // Reload users after editing
            const editModal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
            editModal.hide(); 

            // Show success modal
            const editSuccessModal = new bootstrap.Modal(document.getElementById('editSuccessModal'));
            editSuccessModal.show();
        } else {
            alert('Error saving user: ' + result.message);
        }
    })
    .catch(error => console.error('Error saving user:', error));
});


// Load users when the page loads
document.addEventListener("DOMContentLoaded", loadUsers);




