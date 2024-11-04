// Function to fetch data and populate the work order table
function fetchWorkOrderData() {
    fetch('work_order.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('work-order-data');
                tbody.innerHTML = ''; // Clear existing rows

                data.records.forEach(record => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${record.workorder_ID}</td>
                        <td>${record.assigned_to || '-'}</td>
                        <td>${record.replaced_parts || '-'}</td>
                        <td>${record.activity || '-'}</td>
                        <td>${record.remarks || '-'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                alert('Error fetching work order data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching the work order data.');
        });
}

// Call the function to load data on page load
document.addEventListener('DOMContentLoaded', fetchWorkOrderData);
