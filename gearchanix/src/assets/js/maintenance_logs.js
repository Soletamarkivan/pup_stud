let selectedDeleteLogID = null; // Variable to store the selected log ID for deletion

// Function to load maintenance logs from the server
function loadMaintenanceLogs() {
    fetch('maintenance_Logs.php') // Fetch maintenance logs from the server
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const maintenanceLogBody = document.getElementById('maintenanceLogBody');
            maintenanceLogBody.innerHTML = ''; // Clear existing data

            data.forEach(log => {
                const row = `
                    <tr>
                        <td>${log.log_ID ?? ''}</td>
                        <td>${log.vehicle_type ?? ''}</td>
                        <td>${log.plate_num ?? ''}</td>
                        <td>${log.odometer_last_service ?? ''}</td>
                        <td>${log.date_last_service ?? ''}</td>
                        <td>${log.task_name ?? ''}</td>
                        <td>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" onclick="confirmDelete(${log.log_ID})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                maintenanceLogBody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => console.error('Error loading maintenance logs:', error));
}

// Function to show delete confirmation modal
function confirmDelete(logID) {
    selectedDeleteLogID = logID; // Set the selected log ID
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Confirm deletion
document.getElementById('confirmDelete').addEventListener('click', function() {
    fetch('vehicletriptix.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ log_ID: selectedDeleteLogID }),
    })
    .then(response => response.json())
    .then(data => {
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        deleteModal.hide(); // Hide the modal
        
        if (data.success) {
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => {
                loadMaintenanceLogs(); // Reload the maintenance logs
            }, 2000); // Wait 2 seconds before refreshing the data
        } else {
            alert('Error deleting record: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

// Load the maintenance logs when the page loads
document.addEventListener('DOMContentLoaded', loadMaintenanceLogs);
