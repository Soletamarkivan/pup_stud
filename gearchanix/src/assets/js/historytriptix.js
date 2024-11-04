let selectedDeleteTicketID = null; // Variable to store the selected ticket ID for deletion

// Function to load trip tickets from history_triptix
function loadHistoryTripTickets() {
    fetch('historytrip.php') // Fetch history trip tickets from the server
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tripTicketBody = document.getElementById('tripTicketBody');
            tripTicketBody.innerHTML = ''; // Clear existing data

            data.forEach(ticket => {
                const row = `
                    <tr> 
                        <td>${ticket.trip_ticket_date ?? ''}</td>
                        <td>${ticket.vehicle_type ?? ''}</td>
                        <td>${ticket.plate_num ?? ''}</td>
                        <td>${ticket.gas_tank ?? ''}</td>
                        <td>${ticket.purchased_gas ?? ''}</td>
                        <td>${ticket.total ?? ''}</td>
                        <td>${ticket.start_odometer ?? ''}</td>
                        <td>${ticket.end_odometer ?? ''}</td>
                        <td>${ticket.KM_used ?? ''}</td>
                        <td>${ticket.RFID_Easy ?? ''}</td>
                        <td>${ticket.RFID_Auto ?? ''}</td>
                        <td>${ticket.oil_used ?? ''}</td>
                        <td>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" onclick="confirmDelete(${ticket.trip_ticketID})">
                                <i class="fas fa-trash"></i>
                            </button></td>
                `;
                tripTicketBody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => console.error('Error loading history trip tickets:', error));
}

// Function to show delete confirmation modal
function confirmDelete(tripTicketID) {
    selectedDeleteTicketID = tripTicketID; // Set the selected ticket ID
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
        body: JSON.stringify({ trip_ticketID: selectedDeleteTicketID }),
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
                loadHistoryTripTickets(); // Reload the trip tickets
            }, 2000); // Wait 2 seconds before refreshing the data
        } else {
            alert('Error deleting record: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

// Load the trip tickets when the page loads
document.addEventListener('DOMContentLoaded', loadHistoryTripTickets);
