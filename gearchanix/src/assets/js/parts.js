// Function to get the vehicle_ID from the URL
function getVehicleIDFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('vehicle_ID'); // Get the vehicle_ID from the query parameter
}

// Call the function to load parts when the page loads
document.addEventListener("DOMContentLoaded", () => {
    const vehicleID = getVehicleIDFromURL(); // Get vehicle_ID from URL
    if (vehicleID) {
        loadVehicleParts(vehicleID); // Load parts for the specific vehicle
    } else {
        console.error("Vehicle ID not found in URL.");
    }
});

// Function to load vehicle parts based on the vehicle_ID
function loadVehicleParts(vehicleID) {
    fetch(`vehicleparts.php?vehicle_ID=${vehicleID}`)
        .then(response => response.json())
        .then(data => {
            const partsTableBody = document.getElementById("parts-data");
            partsTableBody.innerHTML = ''; // Clear existing table body

            if (data.length === 0) {
                partsTableBody.innerHTML = '<tr><td colspan="11">No parts found for this vehicle.</td></tr>';
                return;
            }

            data.forEach(part => { 
                const row = document.createElement("tr");

                // Populate the row with part details
                row.innerHTML = `
                    <td>${part.vehicleparts_ID}</td>
                    <td>${part.part_name}</td>
                    <td>${part.part_mtbf}</td>
                    <td>${part.part_ornum}</td>
                    <td>${part.part_date_procurred}</td>
                    <td>${part.part_date_inspected}</td>
                    <td>${part.part_date_accomplished}</td>
                    <td>${part.part_remarks || 'N/A'}</td>
                    <td>${part.part_num_of_days}</td>
                    <td>${part.part_description || 'N/A'}</td>
                    <td>
                        <button class="btn btn-light btn-sm mx-1 btn-edit"  onclick="editPart(${part.vehicleparts_ID})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-light btn-sm mx-1 btn-delete" onclick="deletePart(${part.vehicleparts_ID}, ${vehicleID})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                // Append the row to the table body
                partsTableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error loading vehicle parts:', error));
}

// Function to delete a vehicle part
function deletePart(partID, vehicleID) {
    if (confirm('Are you sure you want to delete this part?')) {
        fetch(`delete_vehiclepart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ vehicleparts_ID: partID })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Part deleted successfully.');
                loadVehicleParts(vehicleID); // Refresh the parts list after deletion
            } else {
                alert('Failed to delete part.');
            }
        })
        .catch(error => console.error('Error deleting part:', error));
    }
}

function editPart(vehicleparts_ID) {
    // Fetch existing part data from the server
    fetch(`get_parts.php?vehicleparts_ID=${vehicleparts_ID}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data && data.vehicleparts_ID) { 
                // Populate the form 
                document.getElementById('edit_vehicleparts_ID').value = data.vehicleparts_ID;
                document.getElementById('edit_part_name').value = data.part_name;
                document.getElementById('edit_part_mtbf').value = data.part_mtbf;
                document.getElementById('edit_part_ornum').value = data.part_ornum || ''; // Ensure it's not null or undefined
                document.getElementById('edit_part_date_procurred').value = data.part_date_procurred || ''; // Ensure it's not null
                document.getElementById('edit_part_date_inspected').value = data.part_date_inspected || ''; // Ensure it's not null
                document.getElementById('edit_part_date_accomplished').value = data.part_date_accomplished || ''; // Ensure it's not null
                document.getElementById('edit_part_num_of_days').value = data.part_num_of_days || 0; // Default to 0 if undefined
                document.getElementById('edit_part_remarks').value = data.part_remarks || '';
                document.getElementById('edit_part_description').value = data.part_description || '';

                // Show the edit modal
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            } else {
                alert('Error: Unable to fetch part data.'); // Notify user of the error
            }
        })
        .catch(error => console.error('Error fetching part data:', error));
}

// Handle save changes button click
document.getElementById('editSaveBtn').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('editPartForm'));

    // Log form data for debugging
    for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch('update_parts.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            editModal.hide();

            // Show success modal
            const EditsuccessModal = new bootstrap.Modal(document.getElementById('EditsuccessModal'));
            EditsuccessModal.show();

            // Optional: Reload the page after a short delay
            setTimeout(() => {
                location.reload();  // Reload the page
            }, 2000); // Adjust the delay as needed
        } else {
            alert('Error: ' + data);
        }
    })
    .catch(error => console.error('Error:', error));
});

/*Functions for export*/
// Function to get the vehicle_ID from the URL
function getVehicleIDFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('vehicle_ID'); // Get the vehicle_ID from the query parameter
}

// Function to get vehicle IDs from the page elements
function getVehicleIDsFromPage() {
    const vehicles = document.querySelectorAll('.vehicle');
    const vehicleIDs = [];

    vehicles.forEach(vehicle => {
        const vehicleID = vehicle.getAttribute('vehicle_ID');
        vehicleIDs.push(vehicleID);
    });

    return vehicleIDs;
}

// Search function
function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("parts-data");
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


