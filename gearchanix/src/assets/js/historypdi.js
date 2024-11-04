$(document).ready(function() {
    // Fetch PDI data on page load
    fetchpdi();

    function fetchpdi() {
        $.ajax({
            url: 'historypdi.php', // Ensure this URL points to your PHP file
            method: 'GET',
            success: function(data) {
                $('#history-pdi-data').html(data); // Update the table body with fetched data
            },
            error: function(xhr, status, error) {
                console.error('Error fetching trip tickets:', error);
            }
        });
    }

    let deletePdiID = null; 

    // Function to open the delete confirmation modal
    window.openDeleteModal = function(pdiID, plateNum) { // Make this function global
        deletePdiID = pdiID; // Store the PDI ID to be deleted
        $('#delete-plate-number').text(plateNum); // Show the plate number in the modal
        $('#deleteModal').modal('show'); // Show the modal
    };

    // Function to confirm deletion using jQuery
    $('#confirmDeleteBtn').on('click', function() {
        if (deletePdiID !== null) {
            // Perform the deletion using AJAX
            $.ajax({
                url: 'delete_pdi.php', // Ensure this PHP script handles deletion
                type: 'POST',
                data: { pdi_ID: deletePdiID },
                success: function(response) {
                    // Show success modal
                    $('#success-plate-number').text($('#delete-plate-number').text()); // Set the plate number in the success modal
                    $('#deleteModal').modal('hide'); // Hide the delete modal
                    $('#successModal').modal('show'); // Show the success modal
                    
                    // Reload the table data or remove the deleted row
                    fetchpdi(); // Call fetchpdi to refresh the table data without reloading the page
                },
                error: function() {
                    alert('Error deleting record.');
                }
            });
        }
    });
});
