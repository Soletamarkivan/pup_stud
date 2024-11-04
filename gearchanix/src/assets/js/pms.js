// Function to fetch data and populate the table
function fetchMaintenanceData() {
    fetch('pms.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('sched-maintenance-data');
                tbody.innerHTML = ''; // Clear existing rows

                data.records.forEach(record => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                    <td>${record.pms_ID}</td>
                    <td>${record.vehicle_ID}</td>
                    <td>${record.vehicle_model}</td>
                    <td>${record.plate_num}</td>
                    <td>${record.acquisition_date}</td>
                    <td>${record.target_date}</td>
                    <td>${record.workorder_ID}</td>
                    <td>${record.parts}</td>
                    <td>${record.service_task}</td>
                    <td><button class="btn btn-primary btn-sm ms-3" onclick="updateMaintenanceLog('${record.plate_num}', '${record.service_task}')">Update</button></td>
                `;
                    tbody.appendChild(row);
                });
            } else {
                alert('Error fetching maintenance data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching the maintenance data.');
        });
}

// Call the function to load data on page load
document.addEventListener('DOMContentLoaded', fetchMaintenanceData);

function updateMaintenanceLog(plateNum, serviceTask) {
    fetch('update_maintenance_logs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            plate_num: plateNum,
            service_task: serviceTask
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Maintenance log updated successfully!");
        } else {
            alert("Error updating maintenance log.");
        }
    })
    .catch(error => console.error('Error:', error));
}
