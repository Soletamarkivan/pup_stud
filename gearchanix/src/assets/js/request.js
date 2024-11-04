document.addEventListener("DOMContentLoaded", loadMaintenanceRequests);

function loadMaintenanceRequests() {
    const tbody = document.getElementById('maintenance-request-data');
    tbody.innerHTML = '<tr><td colspan="14">Loading...</td></tr>';

    fetch('populate_maintenance_request.php')
        .then(response => response.json())
        .then(data => {
            tbody.innerHTML = '';

            if (data.success) {
                data.data.forEach(request => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${request.queue_num}</td>
                        <td>${request.vehicle_name}</td>
                        <td>${request.vehicle_type}</td>
                        <td>${request.plate_num}</td>
                        <td>${request.driver || ''}</td>
                        <td>${request.service_task}</td>
                        <td>${request.start_date || ''}</td>
                        <td>${request.target_date}</td>
                        <td>${request.parts || ''}</td>
                        <td>${request.receiver || ''}</td>
                        <td>${request.date || ''}</td>
                        <td>${request.noted_by || ''}</td>
                        <td>
                            <select class="status-dropdown" data-request-id="${request.queue_num}" onchange="updateApprovalStatus(${request.queue_num}, 'chief', this.value)">
                                <option value="Pending" ${request.approval_chief === 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Approved" ${request.approval_chief === 'Approved' ? 'selected' : ''}>Approve</option>
                                <option value="Rejected" ${request.approval_chief === 'Rejected' ? 'selected' : ''}>Reject</option>
                            </select>
                        </td>
                        <td>
                            <select class="status-dropdown" data-request-id="${request.queue_num}" onchange="updateApprovalStatusOffice(${request.queue_num}, 'office', this.value)">
                                <option value="Pending" ${request.approval_office === 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Approved" ${request.approval_office === 'Approved' ? 'selected' : ''}>Approve</option>
                                <option value="Rejected" ${request.approval_office === 'Rejected' ? 'selected' : ''}>Reject</option>
                            </select>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="14">${data.message}</td></tr>`;
            }
        })
        .catch(error => {
            console.error('Error loading data:', error);
            tbody.innerHTML = `<tr><td colspan="14">Error loading data. Please try again later.</td></tr>`;
        });
}

function updateApprovalStatus(queueNum, type, status) {
    const formData = new FormData();
    formData.append('queue_num', queueNum);
    formData.append('approval_type', type);
    formData.append('approval_status', status);

    fetch('update_approval_chief.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Check if the response is a PDF
        const contentType = response.headers.get('Content-Type');
        if (contentType === 'application/pdf') {
            // Create a blob from the PDF response
            return response.blob().then(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Nature_of_Schedule_of_Repair_${queueNum}.pdf`; // Change this if needed
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
                alert('PDF generated and downloaded successfully.');
            });
        }

        // If it's not a PDF, we can parse it as JSON
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Approval status updated successfully');
            alert('Approval status updated to: ' + status);
        } else {
            console.error('Failed to update approval status:', data.message);
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating approval status:', error);
        alert('An error occurred while updating the approval status. Please try again.');
    });
}

// JavaScript function to send an AJAX request to update approval status
function updateApprovalStatusOffice(queueNum, approvalType, newStatus) {
    fetch('update_approval_office.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            queue_num: queueNum,
            approval_type: approvalType,
            new_status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully!');
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
