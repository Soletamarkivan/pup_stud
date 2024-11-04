document.addEventListener("DOMContentLoaded", function () {
    // Get the table body where service reminders are listed
    const tableBody = document.getElementById("service-reminder-list-data");

    // Function to update row colors based on status
    function updateRowColors() {
        const rows = tableBody.getElementsByTagName("tr");

        // Loop through each row in the table
        for (const row of rows) {
            const statusCell = row.cells[3]; // Assuming "Status" is in the 4th column (index 3)
            
            // Check if the status is "overdue" and apply red background
            if (statusCell && statusCell.textContent.trim().toLowerCase() === "overdue") {
                row.style.backgroundColor = "red";
                row.style.color = "white"; // Optional: Change text color for readability
            } else {
                row.style.backgroundColor = ""; // Reset to default if not overdue
            }
        }
    }

    // Initial color update on page load
    updateRowColors();

    // Optional: Update row colors when data is dynamically loaded/updated
    // Assuming there's an event for new data loading, otherwise re-run updateRowColors after loading new data
});