document.addEventListener('DOMContentLoaded', function () {
    const rowLimitSelect = document.getElementById('rowLimit');
    const infoText = document.getElementById('dataTable_info');
    const pagination = document.querySelector('.pagination');
    let currentPage = 1;

    // Table display and pagination
    function initializeTable(tableBodyId) {
        const tableBody = document.getElementById(tableBodyId);

        function displayTableRows(page, limit) {
            const rows = Array.from(tableBody.getElementsByTagName('tr'));
            const start = (page - 1) * limit;
            const end = start + limit;

            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? '' : 'none';
            });

            infoText.innerText = `Showing ${start + 1} to ${Math.min(end, rows.length)} of ${rows.length}`;
            updatePagination(page, Math.ceil(rows.length / limit));
        }

        function updatePagination(page, totalPages) {
            pagination.innerHTML = ''; 

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

        // Set up initial display and event listeners
        let rowsPerPage = parseInt(rowLimitSelect.value, 10);
        rowLimitSelect.addEventListener('change', function () {
            rowsPerPage = parseInt(rowLimitSelect.value, 10);
            currentPage = 1;
            displayTableRows(currentPage, rowsPerPage);
        });

        displayTableRows(currentPage, rowsPerPage);
    }

    // Initialize tables by passing their table body IDs
    const tableIds = [
        'vehicle-data', 'parts-data', 'latest-list-data', 'pms-list-data',
        'blowbagets-list-data', 'service-task-list-data', 'service-reminder-list-data',
        'sched-maintenance-data', 'maintenance-request-data', 'work-order-data',
        'reservation-data', 'tripTicketBody', 'roles-data', 'drivers-list-data'
    ];
    
    tableIds.forEach(tableId => initializeTable(tableId));
});
