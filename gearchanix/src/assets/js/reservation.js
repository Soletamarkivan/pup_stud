// adding new reservations
document.getElementById("addBtn").addEventListener("click", function() {
    window.location.href = "/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/addreservation.html";
});
// Fetch reservation data and populate the table
function loadReservations() {
    fetch('fetch_reservations.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('reservation-data');
            tableBody.innerHTML = ''; 

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="13">No reservations found</td></tr>'; 
            } else {
                data.forEach(row => {
                    let manifestDownload = row.passenger_manifest
                        ? `<a href="data:image/jpeg;base64,${row.passenger_manifest}" download="passenger_manifest_${row.reservation_date}.jpg">Download</a>`
                        : 'No Attachment';

                    const tableRow = `
                    <tr data-reservation-id="${row.reservation_ID}">
                        <td>
                            <select class="status-dropdown" data-reservation-id="${row.reservation_ID}" onchange="updateReservationStatus(${row.reservation_ID}, this.value)">
                                <option value="Pending" ${row.reservation_status === 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Approved" ${row.reservation_status === 'Approved' ? 'selected' : ''}>Approve</option>
                                <option value="Rejected" ${row.reservation_status === 'Rejected' ? 'selected' : ''}>Reject</option>
                            </select>
                        </td>
                        <td>${row.vehicle_type}</td>
                        <td>${row.reservation_date}</td>
                        <td>${row.location}</td>
                        <td>${row.duration}</td>
                        <td>${row.time_departure}</td>
                        <td>${row.no_passengers}</td>
                        <td>${row.office_dept}</td>
                        <td>${row.email}</td>
                        <td>${row.contact_no}</td>
                        <td>${row.service_type}</td>
                        <td>${row.purpose}</td>
                        <td>${manifestDownload}</td>
                        <td>
                            <button class="btn btn-light btn-sm mx-1 btn-edit" onclick="editReservation(${row.reservation_ID})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" onclick="confirmDelete(${row.reservation_ID})">
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

function updateReservationStatus(reservationID, status) {
    fetch('update_reservation_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            reservation_ID: reservationID,
            reservation_status: status,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // Change this to parse JSON directly
    })
    .then(data => {
        console.log('Response data:', data); // Log the parsed response for debugging
        if (data.success) {
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
            successModal.show();

            console.log(data.message); // Log the message for debugging

            // Apply the dropdown styles dynamically after the update
            const dropdown = document.querySelector(`select[data-reservation-id="${reservationID}"]`);
            applyDropdownStyles(dropdown, status); // Update the styles based on the new status

            // Add event listener to reload the page when the modal is hidden
            document.getElementById('statusSuccessModal').addEventListener('hidden.bs.modal', function () {
                loadReservations(); 
            });

        } else {
            alert(`Error: ${data.message}`);
            console.error(data.message);
        }
    })
    .catch(error => {
        alert('Failed to update reservation status. Please try again.');
        console.error('Error:', error);
    });
}

// Function to dynamically apply styles based on the selected option
function applyDropdownStyles(dropdown, status) {
    // Remove all possible status classes
    dropdown.classList.remove('status-pending', 'status-approved', 'status-rejected');

    if (status === 'Pending') {
        dropdown.classList.add('status-pending');
    } else if (status === 'Approved') {
        dropdown.classList.add('status-approved');
    } else if (status === 'Rejected') {
        dropdown.classList.add('status-rejected');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.status-dropdown').forEach(dropdown => {
        const currentStatus = dropdown.value;
        applyDropdownStyles(dropdown, currentStatus);
    });
});

function updateReservationStatus(reservationID, status) {
    if (status === 'Rejected') {
        // Show the rejection modal to get custom message
        const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        rejectionModal.show();

        // Add event listener for the submission of the custom message
        document.getElementById('submitRejectionMessage').onclick = function() {
            const customMessage = document.getElementById('rejectionMessageInput').value;

            // Call the function again with the custom message
            sendUpdateRequest(reservationID, status, customMessage);

            // Hide the modal after submission
            rejectionModal.hide();
        };
    } else {
        // If not rejected, proceed normally
        sendUpdateRequest(reservationID, status);
    }
}

function sendUpdateRequest(reservationID, status, customMessage = '') {
    fetch('update_reservation_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            reservation_ID: reservationID,
            reservation_status: status,
            custom_message: customMessage // Include the custom message if provided
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // Change this to parse JSON directly
    })
    .then(data => {
        console.log('Response data:', data); // Log the parsed response for debugging
        if (data.success) {
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
            successModal.show();

            console.log(data.message); // Log the message for debugging

            // Add event listener to reload the page when the modal is hidden
            document.getElementById('statusSuccessModal').addEventListener('hidden.bs.modal', function () {
                loadReservations(); 
            });

        } else {
            alert(`Error: ${data.message}`);
            console.error(data.message);
        }
    })
    .catch(error => {
        alert('Failed to update reservation status. Please try again.');
        console.error('Error:', error);
    });
}



let reservationIdToDelete; // Variable to store the ID of the reservation to delete

// Function to show the delete confirmation modal
function confirmDelete(reservationId) {
    reservationIdToDelete = reservationId; // Store the reservation ID
    const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show();
}

// Handle the confirm delete button click
document.getElementById("confirmDelete").addEventListener("click", function() {
    // Make AJAX call to delete the reservation
    fetch(`fetch_reservations.php?del_reserve=${reservationIdToDelete}`)
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // Hide delete confirmation modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
                deleteModal.hide();

                // Show the success modal
                const successModal = new bootstrap.Modal(document.getElementById("successModal"));
                successModal.show();

                // Reload the page after a delay
                setTimeout(() => {
                    window.location.reload(); // Reload the page after a brief delay
                }, 1500);
            } else {
                // Handle any error messages here if necessary
                alert('Error deleting reservation: ' + result.message);
            }
        })
        .catch(error => console.error('Error deleting reservation:', error));
});

// edit action
function editReservation(reservation_ID) {
    fetch(`update_reservation.php?id=${reservation_ID}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Populate the modal fields with the fetched data
                document.getElementById("editReservationID").value = data.reservation_ID;
                document.getElementById("editVehicleType").value = data.vehicle_type;
                document.getElementById("editReservationDate").value = data.reservation_date;
                document.getElementById("editLocation").value = data.location;
                document.getElementById("editDuration").value = data.duration;
                document.getElementById("editTimeDeparture").value = data.time_departure;
                document.getElementById("editNoPassengers").value = data.no_passengers;
                document.getElementById("editOfficeDept").value = data.office_dept;
                document.getElementById("editEmail").value = data.email;
                document.getElementById("editContactNo").value = data.contact_no;
                document.getElementById("editServiceType").value = data.service_type;
                document.getElementById("editPurpose").value = data.purpose;

                // Handle passenger manifest: show link to existing file if available
                if (data.passenger_manifest) {
                    const manifestLink = document.getElementById('manifestLink');
                    manifestLink.innerHTML = `<a href="${data.passenger_manifest}" target="_blank">View current manifest</a>`;
                } else {
                    document.getElementById('manifestLink').innerHTML = '';
                }

                const editModal = new bootstrap.Modal(document.getElementById("editModal"));
                editModal.show();
            } else {
                alert('Error: Could not fetch reservation details.');
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Submit the edited reservation
document.getElementById("editReservationForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    const formData = new FormData(this); // Get form data
    const reservationId = document.getElementById("editReservationID").value; // Get the selected reservation ID

    // Send the updated data to the server
    fetch(`update_reservation.php?id=${reservationId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Hide the edit modal
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            editModal.hide(); // Hide the edit modal

            // Show the edit success modal
            const successModal = new bootstrap.Modal(document.getElementById('editSuccessModal'));
            successModal.show();

            // Optional: Reload the reservations or update the UI as needed
            loadReservations(); // Call this function if you have it to refresh the reservation list
        } else {
            // Show an error modal or an alert with the error message
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            document.getElementById('errorModalMessage').innerText = 'Error updating reservation: ' + result.message;
            errorModal.show();
        }
    })
    .catch(error => {
        console.error('Error updating reservation:', error);
        // Show an error modal for catch block as well
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        document.getElementById('errorModalMessage').innerText = 'Error updating reservation: ' + error.message;
        errorModal.show();
    });
});

// Search function
function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("reservation-data");
    const rows = table.getElementsByTagName("tr");
    let hasResult = false;  

    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;
        
        for (let j = 0; j < cells.length; j++) {
            if (cells[j] && cells[j].innerText.toLowerCase().indexOf(input) > -1) {
                match = true;
                break;  
            }
        }
        rows[i].style.display = match ? "" : "none";  
        if (match) hasResult = true; 
    }

    // Display a "No result" message if no rows match
    const noResultRow = document.getElementById("no-result-row");
    if (!hasResult) {
        if (!noResultRow) {
            const newRow = document.createElement("tr");
            newRow.id = "no-result-row";
            const newCell = document.createElement("td");
            newCell.colSpan = rows[0].children.length;  
            newCell.style.textAlign = "center";
            newCell.textContent = "No result";
            newRow.appendChild(newCell);
            table.appendChild(newRow);
        }
    } else if (noResultRow) {
        noResultRow.remove(); 
    }
}


window.onload = loadReservations;


