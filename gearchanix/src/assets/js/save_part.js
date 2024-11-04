// Get vehicle_ID from URL
function getVehicleID() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('vehicle_ID');
  }
  
  // Show the modal when the add button is clicked
  document.getElementById('addBtn').addEventListener('click', function() {
    const vehicleID = getVehicleID();
    if (!vehicleID) {
      alert('Vehicle ID not found in URL');
      return;
    }
    // Show the modal
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    addModal.show();
  });
  
  
// Handle save button click
document.getElementById('saveBtn').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('addPartForm'));
    formData.append('vehicle_ID', getVehicleID());  // Add vehicle_ID to the form data
  
    fetch('save_part.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      if (data === 'success') {

        const addModal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
        addModal.hide();

        // Show success modal
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        // Reload the page after a short delay
        setTimeout(() => {
          location.reload();  // Reload the page
        }, 2000); // Adjust the delay as needed
      } else {
        alert('Error: ' + data);
      }
    })
    .catch(error => console.error('Error:', error));
  });
  