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
                        <button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="editPart(${part.vehicleparts_ID})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-light btn-sm mx-1 btn-delete" title="Delete" onclick="deletePart(${part.vehicleparts_ID}, ${vehicleID})">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-warning btn-sm mx-1" onclick="editPart(${part.vehicleparts_ID})">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1" onclick="deletePart(${part.vehicleparts_ID}, ${vehicleID})">Delete</button>
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

// Function to edit a vehicle part
function editPart(partID) {
    // Redirect to the edit page with the part ID
    window.location.href = `/gearchanix/edit_part.php?vehicleparts_ID=${partID}`;
}