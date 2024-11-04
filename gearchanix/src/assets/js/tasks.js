// Wait for the DOM content to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch and load service tasks
    function loadServiceTasks() {
        fetch('tasks.php')
            .then(response => response.json())
            .then(data => {
                const taskTableBody = document.getElementById("service-task-list-data");
                taskTableBody.innerHTML = ''; 

                // Create rows for each task and append to the table
                data.forEach(task => { 
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${task.task_ID}</td>
                        <td>${task.task_name}</td>
                        <td>${task.description}</td>
                        <td>${task.MTBF}</td>
                        <td>${task.parts_involved}</td>
                        <td>
                            <button class="btn btn-light btn-sm mx-1 btn-edit" title="Edit" onclick="editTask(${task.task_ID})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-sm mx-1 btn-delete" title="Delete" onclick="confirmDelete(${task.task_ID})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    taskTableBody.appendChild(row); 
                });
            })
            .catch(error => console.error('Error loading task data:', error)); 
    }

    // Initial load of service tasks
    loadServiceTasks();

    // Event listener for adding a new service task
    document.getElementById('addTaskForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(this); 

        fetch('addtask.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); 
        })
        .then(data => {
            if (data.success) {
                // Hide the add task modal
                const addTaskModalElement = document.getElementById('addtaskModal');
                const addTaskModal = bootstrap.Modal.getInstance(addTaskModalElement);
                addTaskModal.hide();

                // Reload the task list to reflect the new addition
                loadServiceTasks();

                // Show success modal
                const addSuccessModalElement = document.getElementById('addSuccessModal');
                const addSuccessModal = new bootstrap.Modal(addSuccessModalElement);
                addSuccessModal.show();

                // Optional: reload the page after 2 seconds
                setTimeout(() => {
                    location.reload(); 
                }, 2000); 
            } else {
                alert(data.message); // Show error message if not successful
            }
        })
        .catch(error => {
            console.error('Error adding service task:', error);
            alert('An error occurred: ' + error.message); 
        });
    });

    // Global variable to store the task ID to be deleted
    let taskToDelete = null;

    // Function to show delete confirmation modal
    window.confirmDelete = function(task_ID) {
        taskToDelete = task_ID;  // Store the task ID globally
        const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteConfirmationModal.show(); // Show the delete confirmation modal
    };

    // Event listener for the 'Delete' button inside the modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (taskToDelete !== null) {
            fetch(`deleteTask.php?id=${taskToDelete}`, {
                method: 'GET',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Hide the delete confirmation modal
                    const deleteConfirmationModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteConfirmationModal.hide();

                    // Reload the task list to reflect the deletion
                    loadServiceTasks();

                    // Show Delete Success Modal
                    const deleteSuccessModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
                    deleteSuccessModal.show();

                    // Optionally, refresh the page after the modal is shown
                    setTimeout(() => {
                        location.reload();  // Reload the page to show updated data
                    }, 2000); // Delay of 2 seconds (adjust as necessary)
                } else {
                    alert(data.message); // Show error message if not successful
                }
            })
            .catch(error => {
                console.error('Error deleting task:', error);
                alert('An error occurred: ' + error.message); 
            });
        }
    });

    // Function to edit a task
    window.editTask = function(task_ID) {
        fetch(`edittask.php?id=${task_ID}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Populate the modal fields with the fetched data
                    document.getElementById("editTaskID").value = task_ID; // Set the hidden field
                    document.getElementById("editTaskName").value = data.task_name || '';
                    document.getElementById("editDescription").value = data.description || '';
                    document.getElementById("editMTBF").value = data.MTBF || '';
                    document.getElementById("editPartsInvolved").value = data.parts_involved || '';

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                } else {
                    console.error('No data found for the task ID:', task_ID);
                }
            })
            .catch(error => {
                console.error('Error fetching task data:', error);
            });
    };
    
    // Event listener for edit task form submission
    document.getElementById('editTaskForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
    
        const formData = new FormData(this);
        
        // Ensure the task_ID is included in the formData
        const taskID = document.getElementById("editTaskID").value;
        formData.append("task_ID", taskID); // Add task_ID to the FormData
    
        fetch('editTask.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); 
        })
        .then(data => {
            if (data.success) {
                const editTaskModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                editTaskModal.hide();

                loadServiceTasks();

                const editSuccessModal = new bootstrap.Modal(document.getElementById('editSuccessModal'));
                editSuccessModal.show(); 
                setTimeout(() => location.reload(), 2000);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error updating task:', error);
            alert('An error occurred: ' + error.message); 
        });
    });
    
});


document.getElementById("searchInput").addEventListener("input", searchFunction);

function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("service-task-list-data");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j] && cells[j].innerText.toLowerCase().indexOf(input) > -1) {
                match = true;
                break; // Exit inner loop if match found
            }
        }
        rows[i].style.display = match ? "" : "none";
    }
}