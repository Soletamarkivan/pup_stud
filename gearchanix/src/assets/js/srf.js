document.addEventListener("DOMContentLoaded", loadServiceReminders);

function loadServiceReminders() {
    const tbody = document.getElementById('service-reminder-list-data');
    tbody.innerHTML = '<tr><td colspan="11">Loading...</td></tr>';

    fetch('populate_service_reminder.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not Okay');
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';

            data.forEach(reminder => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${reminder.vehicle_type ?? ''}</td>
                    <td>${reminder.plate_num ?? ''}</td>
                    <td>${reminder.service_task ?? ''}</td>
                    <td>${reminder.status ?? ''}</td>
                    <td>${reminder.next_due ?? ''}</td>
                    <td>${reminder.meter_until_due ?? ''}</td>
                    <td>${reminder.est_days !== null ? reminder.est_days : ''}</td>
                    <td>${reminder.pms_date ?? ''}</td>
                    <td>${reminder.latest_odometer ?? ''}</td>
                    <td>${reminder.parts ?? ''}</td>
                    <td><button class="btn btn-primary btn-sm ms-3" onclick="scheduleRepair(${reminder.reminder_ID})">Schedule</button></td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            tbody.innerHTML = '<tr><td colspan="11">Error loading data</td></tr>';
        });
}

function scheduleRepair(reminder_ID) {
    const targetDateInput = document.getElementById('targetDate');

    // Show the date modal
    $('#dateModal').modal('show');

    // Submit date button
    document.getElementById('submitDate').onclick = function () {
        const targetDate = targetDateInput.value;

        // Validate the date
        if (!targetDate || !/^\d{4}-\d{2}-\d{2}$/.test(targetDate)) {
            alert("Invalid date format. Please enter the date in YYYY-MM-DD format.");
            return;
        }

        console.log(`Sending target_date: ${targetDate} and reminder_ID: ${reminder_ID}`);

        fetch('set_target_date.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                reminder_ID,
                target_date: targetDate,
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response from PHP:', data);
            if (data.success) {
                $('#dateModal').modal('hide'); // Hide the date modal
                $('#successModal').modal('show'); // Show the success modal
                loadServiceReminders();
            } else {
                alert('Failed to set target date: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while setting the target date.');
        });
    };
}

