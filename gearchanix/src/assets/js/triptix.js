
// Function to load trip tickets from the server
function loadTripTickets() {
    fetch('fetch_trip_tickets.php') // Fetch the trip tickets
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
                            <button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="openEditModal(${ticket.trip_ticketID})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" title="Delete" onclick="confirmDelete(${ticket.trip_ticketID})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-light btn-sm mx-1 btn-move-history" title="Move to History" onclick="moveToHistory(${ticket.trip_ticketID})">
                                <i class="fas fa-archive"></i>
                            </button>
                        </td>
                    </tr> 
                `;
                tripTicketBody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => console.error('Error loading trip tickets:', error));
}


let selectedTripTicketID = null; // Variable to store the selected trip ticket ID

// Function to show move to history modal
function moveToHistory(tripTicketID) {
    selectedTripTicketID = tripTicketID; // Set the selected ID
    const moveToHistoryModal = new bootstrap.Modal(document.getElementById('moveToHistoryModal'));
    moveToHistoryModal.show();
}

// Confirm moving to history
document.getElementById('confirmMoveToHistory').addEventListener('click', function() {
    fetch('move_to_history.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ trip_ticketID: selectedTripTicketID }),
    })
    .then(response => response.json())
    .then(data => {
        const moveToHistoryModal = bootstrap.Modal.getInstance(document.getElementById('moveToHistoryModal'));
        moveToHistoryModal.hide(); // Hide the modal
        
        if (data.success) {
            // Show success modal
            const successMoveModal = new bootstrap.Modal(document.getElementById('successMoveModal'));
            successMoveModal.show();
            setTimeout(() => {
                location.reload(); // Refresh the page to update the records
            }, 2000); // Wait 2 seconds before refreshing
        } else {
            alert('Error moving record to history: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});


function openEditModal(tripTicketID) {
    fetch(`fetch_trip_ticket.php?id=${tripTicketID}`)
        .then(response => response.json())
        .then(data => {
            // Populate the edit modal fields
            document.getElementById('editTripTicketID').value = data.trip_ticketID;
            document.getElementById('editPlateNum').value = data.plate_num;
            document.getElementById('editGasTank').value = data.gas_tank;
            document.getElementById('editPurchasedGas').value = data.purchased_gas;
            document.getElementById('editTotalGas').value = data.total;
            document.getElementById('editStartOdometer').value = data.start_odometer;
            document.getElementById('editEndOdometer').value = data.end_odometer;
            // document.getElementById('editKMUsed').value = data.KM_used;
            document.getElementById('editRFIDEasy').value = data.RFID_Easy;
            document.getElementById('editRFIDAuto').value = data.RFID_Auto;
            document.getElementById('editOilUsed').value = data.oil_used;

            // Load the plate numbers based on vehicle type
            loadPlateNumbers(data.vehicle_type);

            const editModal = new bootstrap.Modal(document.getElementById("editModal"));
            editModal.show();
        })
        .catch(error => console.error('Error fetching trip ticket data:', error));
}


// Global variable to hold trip ticket ID for deletion
let tripTicketIdToDelete;

// Confirm delete function
function confirmDelete(trip_ticketID) {
    tripTicketIdToDelete = trip_ticketID;
    const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show();
}

// Handle confirm delete button click
document.getElementById('confirmDelete').addEventListener("click", function() {
    fetch('delete_trip_ticket.php', { // Ensure the path is correct
        method: 'DELETE',
        body: JSON.stringify({ id: tripTicketIdToDelete }), // Pass ID as JSON
        headers: {
            'Content-Type': 'application/json' // Specify content type as JSON
        }
    })
    .then(response => response.json()) // Parse JSON response
    .then(data => {
        if (data.success) {
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            deleteModal.hide();

            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Reload trip tickets after a brief delay
            setTimeout(() => {
                loadTripTickets(); // Function to reload the trip tickets list
            }, 1500);
        } else {
            alert('Error deleting trip ticket: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting trip ticket:', error);
    });
});


// Attach event listener to vehicle type dropdown
// document.getElementById('editVehicleType').addEventListener('change', function() {
//     const vehicleType = this.value;
//     loadPlateNumbers(vehicleType);
// });
function loadPlateNumbers(vehicleType) {
    const plateNumSelect = document.getElementById('editPlateNum');
    plateNumSelect.innerHTML = '<option value="">Loading...</option>'; // Loading state

    fetch(`fetch_plate_numbers.php?vehicle_type=${vehicleType}`)
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                console.error('Error:', data.message);
                plateNumSelect.innerHTML = '<option value="">Error loading plate numbers</option>';
            } else {
                plateNumSelect.innerHTML = '<option value="">Select Plate Number</option>'; // Reset options
                data.forEach(plate => {
                    plateNumSelect.insertAdjacentHTML('beforeend', `<option value="${plate.plate_num}">${plate.plate_num}</option>`);
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            plateNumSelect.innerHTML = '<option value="">Error loading plate numbers</option>';
        });
}




// Handle edit trip ticket form submission
document.getElementById('editTripTicketForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    fetch('update_trip_ticket.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Check if the response is ok and handle errors
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Hide the edit modal
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            editModal.hide();

            // Show success modal
            const editSuccessModal = new bootstrap.Modal(document.getElementById('editSuccessModal'));
            editSuccessModal.show();

            // Reload the trip ticket table
            loadTripTickets(); // Reload trip tickets
        } else {
            alert('Error updating trip ticket: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating trip ticket:', error);
        alert('There was a problem with your request: ' + error.message);
    });
});




// Initial load of trip tickets
document.addEventListener('DOMContentLoaded', loadTripTickets);


// Function to fetch vehicle data
function loadVehicles() {
    fetch('vehiclelist.php')
        .then(response => response.json())
        .then(data => {
            const vehicleTableBody = document.getElementById("vehicle-data");
            vehicleTableBody.innerHTML = ''; 

            data.forEach(vehicle => { 
                console.log("Vehicle ID:", vehicle.vehicle_ID); // Log to check if vehicle data is correct
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${vehicle.vehicle_status || 'N/A'}</td> 
                    <td>${vehicle.vehicle_name}</td>
                    <td>${vehicle.vehicle_model}</td>
                    <td>${vehicle.vehicle_year}</td>
                    <td>${vehicle.vehicle_vin}</td>
                    <td>${vehicle.vehicle_type}</td>
                    <td>${vehicle.plate_num}</td>
                    <td>${vehicle.lifespan}</td>
                    <td>${vehicle.current_meter}</td>
                    <td>${vehicle.pms_date || 'N/A'}</td> 
                    <td>${vehicle.vehicle_remarks || ''}</td>
                    <td>
                        <button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="editVehicle(${vehicle.vehicle_ID})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-light btn-sm mx-1 btn-delete" title="Delete" onclick="confirmDelete(${vehicle.vehicle_ID})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick="viewParts(${vehicle.vehicle_ID})" title="View Parts">
                        View Parts
                        </button>
                    </td>
                `;
                vehicleTableBody.appendChild(row); 
            }); // End of data.forEach loop
        })
        .catch(error => console.error('Error loading vehicle data:', error)); // Catch block moved to correct location
}

// Function to redirect to the vehicle parts page
function viewParts(vehicleID) {
    window.location.href = `/gearchanix/gearchanix-main/gearchanix/public/pages/admin-clerk/ac-vehicle-parts.html?vehicle_ID=${vehicleID}`;
}


// Call loadVehicles on page load or when necessary
document.addEventListener("DOMContentLoaded", loadVehicles);


// Function to show edit modal with vehicle data
function editVehicle(vehicle_ID) {
    fetch(`editvehicle.php?id=${vehicle_ID}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Populate the modal fields with the fetched data
                document.getElementById("editVehicleID").value = data.vehicle_ID || '';
                document.getElementById("editVehicleName").value = data.vehicle_name || '';
                document.getElementById("editVehicleModel").value = data.vehicle_model || '';
                document.getElementById("editVehicleYear").value = data.vehicle_year || '';
                document.getElementById("editVehicleVin").value = data.vehicle_vin || '';
                document.getElementById("editVehicleType").value = data.vehicle_type || '';
                document.getElementById("editPlateNum").value = data.plate_num || '';
                document.getElementById("editLifespan").value = data.lifespan || '';
                document.getElementById("editCurrentMeter").value = data.current_meter || '';
                document.getElementById("editRemarks").value = data.vehicle_remarks || '';

                
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            } else {
                console.error('No data found for the vehicle ID:', vehicle_ID);
            }
        })
        .catch(error => {
            console.error('Error fetching vehicle data:', error);
        });
}

let vehicleIdToDelete; // Variable to store the ID of the vehicle to delete

// Function to confirm deletion
function confirmDelete(vehicle_ID) {
    vehicleIdToDelete = vehicle_ID; // Store the ID of the vehicle to delete
    const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show(); 
}

// Handle the confirm delete button click
document.getElementById("confirmDelete").addEventListener("click", function() {
    // Make AJAX call to delete the vehicle
    fetch(`deletevehicle.php`, {
        method: 'DELETE',
        body: new URLSearchParams({ id: vehicleIdToDelete }) 
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide delete confirmation modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            deleteModal.hide();

            
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Reload the page after a brief delay
            setTimeout(() => {
                window.location.reload(); 
            }, 1500);
        } else {
            // Handle any error messages here if necessary
            alert('Error deleting vehicle: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting vehicle:', error);
    });
});


// Event listener for edit form submission
document.getElementById("editVehicleForm").addEventListener("submit", function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);

    fetch('editvehicle.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            loadVehicles(); 
            const editModal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
            editModal.hide(); 
        } else {
            alert('Error saving vehicle: ' + result.message);
        }
    })
    .catch(error => console.error('Error saving vehicle:', error));
});



// Function to handle form submission for adding a vehicle
document.getElementById('addVehicleForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const formData = new FormData(this); 

    fetch('addvehicle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {
            $('#addVehicleModal').modal('hide'); // Hide the add vehicle modal
            loadVehicles(); // Reload vehicles to reflect the new addition

            // Show success modal
            const addSuccessModal = new bootstrap.Modal(document.getElementById('addSuccessModal'));
            addSuccessModal.show(); // Show the success modal

            
        } else {
            alert(data.message); // Show error message if not successful
        }
    })
    .catch(error => {
        console.error('Error adding vehicle:', error);
        alert('An error occurred: ' + error.message); 
    });
});

document.getElementById("open-popup-btn").onclick = function(event) {
    event.preventDefault();

    let exportModal = bootstrap.Modal.getInstance(document.getElementById("exportModal"));
    exportModal.hide();

    new bootstrap.Modal(document.getElementById("exportSuccessModal")).show();

    document.getElementById("exportForm").submit();
    
    setTimeout(() => location.reload(), 2000); 
};

/*document.getElementById("open-popup-btn").onclick = function(event) {
    event.preventDefault();

    let exportModal = bootstrap.Modal.getInstance(document.getElementById("exportModal"));
    exportModal.hide();

    let successModal = new bootstrap.Modal(document.getElementById("exportSuccessModal"));
    successModal.show();

    document.getElementById("exportForm").submit();

    // Refresh the page 
    document.getElementById("exportSuccessModal").addEventListener('hidden.bs.modal', function () {
        location.reload();
    });

    //setTimeout(() => location.reload(), 2000); 
};*/




// Search function
function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("tripTicketBody");
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


