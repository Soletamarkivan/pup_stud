$(document).ready(function() {
    // Fetch trip ticket data on page load
    fetchTripTickets();

    function fetchTripTickets() {
        $.ajax({
            url: 'fetch_trip_tickets.php', // Ensure this URL points to your PHP file
            method: 'GET',
            success: function(data) {
                $('#latest-list-data').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching trip tickets:', error);
            }
        });
    }

    // Handle save changes button click
    $('#saveChanges').click(function() {
        // Collect form data
        const pdi_ID = $('#pdi_id').val();
        const driver = $('#driverInput').val();
        const mechanic = $('#mechanicInput').val();
        const pdi_date = $('#pdiDateInput').val();
        const remarks = $('#remarksInput').val()

        // Validate form input before sending
        if (!driver || !mechanic || !pdi_date || !remarks) {
            alert('All fields must be filled out!');
            return;
        }

        // Send the updated data to the server via AJAX
        $.ajax({
            url: 'update_pdi_form.php', // PHP file to update the record
            type: 'POST',
            data: {
                pdi_ID: pdi_ID,
                driver: driver,
                mechanic: mechanic,
                pdi_date: pdi_date,
                remarks: remarks
            },
            success: function(response) {
                if (response === 'success') {
                    // Close the edit modal
                    $('#editModal').modal('hide');
                    
                    // Show the success modal and reload the page after 2 seconds
                    showSuccessModal();
                } else {
                    alert('Failed to update the record');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating PDI form:', error);
            }
        });
    }); 

    // Function to open the edit modal and populate it with the current record's data
    window.editPdiForm = function(pdi_ID) {
        $.ajax({
            url: 'get_pdi_form.php', // PHP file to fetch the record
            type: 'GET',
            data: { pdi_ID: pdi_ID },
            success: function(response) {
                const data = JSON.parse(response);
                $('#pdi_id').val(data.pdi_ID);
                $('#driverInput').val(data.driver);
                $('#mechanicInput').val(data.mechanic);
                $('#pdiDateInput').val(data.pdi_date);
                $('#remarksInput').val(data.remarks);
                
                // Open the edit modal
                $('#editModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching PDI form:', error);
            }
        });
    };

    // Function to open the BLOWBAGETS checklist modal
    window.openChecklist = function(pdiId) {
        // Reset checkboxes (optional, to clear previous selections)
        $('#checklistForm')[0].reset();

        // Fetch current checklist data from the server
        $.ajax({
            url: 'get_checklist_data.php', // PHP script to fetch current checklist data
            type: 'GET',
            data: { pdi_id: pdiId },
            dataType: 'json',
            success: function(data) {
                $('#pdi_id').val(data.pdi_ID);

                // Check the corresponding checkboxes
                $('input[name="battery"]').prop('checked', data.battery);
                $('input[name="lights"]').prop('checked', data.lights);
                $('input[name="oil_level"]').prop('checked', data.oil_level);
                $('input[name="water_level"]').prop('checked', data.water_level);
                $('input[name="brakes"]').prop('checked', data.brakes);
                $('input[name="air_pressure"]').prop('checked', data.air_pressure);
                $('input[name="gas"]').prop('checked', data.gas);
                $('input[name="electronic"]').prop('checked', data.electronic);
                $('input[name="tools"]').prop('checked', data.tools);
                $('input[name="self"]').prop('checked', data.self);

                // Show the modal
                $('#checklistModal').modal('show');
            },
            error: function() {
                alert('Error fetching checklist data.');
            }
        });
    };

    // Handle checklist save button click
    $('#saveChecklist').click(function() {
        const pdiId = $('#pdi_id').val();
        const checklistData = {
            pdi_id: pdiId, // Include pdi_id in the data object
            battery: $('input[name="battery"]').is(':checked') ? 1 : 0,
            lights: $('input[name="lights"]').is(':checked') ? 1 : 0,
            oil_level: $('input[name="oil_level"]').is(':checked') ? 1 : 0,
            water_level: $('input[name="water_level"]').is(':checked') ? 1 : 0,
            brakes: $('input[name="brakes"]').is(':checked') ? 1 : 0,
            air_pressure: $('input[name="air_pressure"]').is(':checked') ? 1 : 0,
            gas: $('input[name="gas"]').is(':checked') ? 1 : 0,
            electronic: $('input[name="electronic"]').is(':checked') ? 1 : 0,
            tools: $('input[name="tools"]').is(':checked') ? 1 : 0,
            self: $('input[name="self"]').is(':checked') ? 1 : 0,
        };

        // Send data to the server to update the checklist
        $.ajax({
            url: 'update_checklist.php', // Your PHP script to update the checklist
            type: 'POST',
            data: checklistData,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.success) {
                    // Hide the checklist modal
                    $('#checklistModal').modal('hide');
                    // Show the success modal and reload the page after 2 seconds
                    showSuccessModal();
                } else {
                    alert('Failed to update checklist.');
                }
            },
            error: function() {
                alert('Error saving checklist.');
            }
        });
    });

    // Function to show the success modal and reload the page after 2 seconds
    function showSuccessModal() {
        $('#successModal').modal('show');

        // Reload the page after 2 seconds (2000 milliseconds)
        setTimeout(function() {
            location.reload();
        }, 2000);
    }
});
