document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('vehicle-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Vehicle Parts
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('parts-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// PDI 
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('latest-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// PMS 
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('pms-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// EMS 
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('blowbagets-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Service Task 
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('service-task-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Service Reminder 
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('service-reminder-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Sched Maintenance
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('sched-maintenance-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});


// Maintenance Request
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('maintenance-request-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Sched Maintenance
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('work-order-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Vehicle Reservation
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('reservation-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});


// Vehicle Trip Ticket
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('tripTicketBody');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Roles - Admin
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('roles-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});

// Roles - Driver
document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const tableBody = document.getElementById('drivers-list-data');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');

    let currentPage = 1;
    let rowsPerPage = parseInt(rowLimitSelect.value, 10);

    // Function to display rows based on page and limit
    function displayTableRows(page, limit) {
        const rows = Array.from(tableBody.getElementsByTagName('tr')); // Fetch rows every time
        const start = (page - 1) * limit;
        const end = start + limit;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';  // Show rows within the limit
            } else {
                row.style.display = 'none';  // Hide other rows
            }
        });

        // Update information text (e.g., "Showing 1 to 5 of 25")
        infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;

        // Update pagination buttons
        updatePagination(page, Math.ceil(rows.length / limit));
    }

    // Function to create and update pagination
    function updatePagination(page, totalPages) {
        pagination.innerHTML = '';  // Clear current pagination

        // Previous button
        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (page === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a>`;
        prevPageItem.addEventListener('click', () => {
            if (page > 1) displayTableRows(page - 1, rowsPerPage);
        });
        pagination.appendChild(prevPageItem);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === page ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', () => {
                displayTableRows(i, rowsPerPage);
            });
            pagination.appendChild(pageItem);
        }

        // Next button
        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (page === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a>`;
        nextPageItem.addEventListener('click', () => {
            if (page < totalPages) displayTableRows(page + 1, rowsPerPage);
        });
        pagination.appendChild(nextPageItem);
    }

    // Event listener for the row limit dropdown
    rowLimitSelect.addEventListener('change', function () {
        rowsPerPage = parseInt(rowLimitSelect.value, 10);
        currentPage = 1;  // Reset to first page when limit changes
        displayTableRows(currentPage, rowsPerPage);
    });

    // Initial display of rows
    displayTableRows(currentPage, rowsPerPage);
});







