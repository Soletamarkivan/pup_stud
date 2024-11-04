<?php
// Database connection details
$host = '127.0.0.1';
$dbName = 'gearchanix';
$username = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from trip_ticket table
$sql = "SELECT trip_ticket_date, vehicle_type, plate_num, gas_tank, purchased_gas, total, start_odometer, end_odometer, KM_used, RFID_Easy, RFID_Auto, oil_used FROM history_triptix";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        
        <!--Links-->
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/css/styles.css">
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/css/search.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <title>Vehicle Trip Ticket</title>
    </head> 

    <body id="page-top">
        <div id="wrapper">
            <!--Sidebar-->
            <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: linear-gradient(rgb(162,25,28) 0%, #9e0f12 99%, #ffffff 100%), var(--bs-red);">
                <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                        <div class="sidebar-brand-icon rotate-n-15"></div>
                        <div class="sidebar-brand-text mx-3"><span>GEARCHANIX</span></div>
                    </a>
                    <hr class="sidebar-divider my-0">
                    <ul class="navbar-nav text-light" id="accordionSidebar">
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/supervisor.html"><i class="fas fa-tachometer-alt"></i><span>DASHBOARD</span></a></li>
                        
                        <!-- Roles Drop Down -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRoles" aria-expanded="false" aria-controls="collapseRoles"><i class="fas fa-user-tie"></i><span>ROLES</span></a>
                            <div id="collapseRoles" class="collapse" aria-labelledby="headingRoles" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/supervisor/roles/admin-list.html"><i class="fas fa-user-circle"></i> ADMIN LIST</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/supervisor/roles/drivers-list.html"><i class="fas fa-car"></i> DRIVER LIST</a>
                                </div>
                            </div>          
                        </li>
                        
                        <!-- Vehicle Drop Down -->
                        <li class="nav-item">
                            <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicles" aria-expanded="false" aria-controls="collapseVehicles"><i class="fas fa-car"></i><span>VEHICLES</span></a>
                            <div id="collapseVehicles" class="collapse" aria-labelledby="headingVehicles" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/supervisor/vehicles/vehicle-list.html"><i class="fas fa-list"></i> VEHICLE LIST</a>
                                    <a class="collapse-item active" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/vehicles/vehicle-history.html"><i class="fas fa-history"></i> VEHICLE HISTORY</a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Maintenance Drop Down -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMaintenance" aria-expanded="false" aria-controls="collapseMaintenance"><i class="fas fa-tools"></i><span>MAINTENANCE</span></a>
                            <div id="collapseMaintenance" class="collapse" aria-labelledby="headingMaintenance" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/maintenance/inspection/inspection.html"><i class="fas fa-check-circle"></i> INSPECTION</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/maintenance/service/service.html"><i class="fas fa-wrench"></i> SERVICE</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/maintenance/maintenance-request.html"><i class="fas fa-clipboard-list"></i> MAINTENANCE REQUEST</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/maintenance/service-maintenance/service-maintenance.html"><i class="fas fa-hammer"></i> SERVICE MAINTENANCE</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/documents/documents.html"><i class="fas fa-book-open"></i><span>DOCUMENTS</span></a></li>
                    </ul>
                </div>
            </nav>
            <!--Content Wrapper-->
            <div class="d-flex flex-column" id="content-wrapper">
                <!--Scrollable Content-->
                <div id="content">
                    <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                        <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-file-export"></i></button>
                            <ul class="navbar-nav flex-nowrap ms-auto">
                                <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                        <form class="me-auto navbar-search w-100">
                                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                                <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                                <!--Profile-->
                                <div class="d-none d-sm-block topbar-divider"></div>
                                <li class="nav-item dropdown no-arrow">
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Chief TMPS</span><img class="border rounded-circle img-profile" src="assets/img/avatars/example.jpg"></a>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                            <a class="dropdown-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/supervisor/profile-supervisor.html">
                                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/login-reg/logout.php">
                                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <!--Scrollable Container-->
                    <div class="scrollable-container">
                        <div class="container-fluid">
                            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                                <h3 class="text-dark mb-0">Trip Ticket List</h3>
                            </div>
                            <div class="row align-items-center">
                                <!-- Search bar -->
                                <div class="col d-flex justify-content-center">
                                    <form class="navbar-search" onsubmit="event.preventDefault(); searchFunction();">
                                        <div class="input-group">
                                            <input id="searchInput" class="form-control search-input" type="text" placeholder="Search" onkeyup="searchFunction()">
                                            <button class="btn btn-primary py-0" type="button" onclick="searchFunction()"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>                                                                      
                                </div>
                                <!-- Export and action buttons -->
                                <div class="col-auto d-flex align-items-center justify-content-end">
                                    <a class="btn btn-primary btn-sm ms-3" role="button" data-bs-toggle="modal" data-bs-target="#exportModal">
                                        <i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report
                                    </a>
                                </div>
                                <!-- Export Modal -->
                                <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exportModalLabel">Select Month(s) and Year</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="exportForm" action="export_historytriptix.php" method="GET">
                                                    <div class="mb-3">
                                                        <div style="text-align: center;">
                                                            <label class="form-label">Select Columns to Include</label><br>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-md-6">
                                                                <input type="checkbox" id="trip_ticket_date" name="columns[]" value="trip_ticket_date">
                                                                <label for="trip_ticket_date">Date</label><br>
                                                                <input type="checkbox" id="vehicle_type" name="columns[]" value="vehicle_type">
                                                                <label for="vehicle_type">Vehicle Type</label><br>
                                                                <input type="checkbox" id="plate_num" name="columns[]" value="plate_num">
                                                                <label for="plate_num">Plate Number</label><br>
                                                                <input type="checkbox" id="gas_tank" name="columns[]" value="gas_tank">
                                                                <label for="gas_tank">Gas Tank</label><br>
                                                                <input type="checkbox" id="purchased_gas" name="columns[]" value="purchased_gas">
                                                                <label for="purchased_gas">Purchased Gas</label><br>
                                                                <input type="checkbox" id="total" name="columns[]" value="total">
                                                                <label for="total">Total Gas</label><br>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="checkbox" id="start_odometer" name="columns[]" value="start_odometer">
                                                                <label for="start_odometer">Start Odometer</label><br>
                                                                <input type="checkbox" id="end_odometer" name="columns[]" value="end_odometer">
                                                                <label for="end_odometer">End Odometer</label><br>
                                                                <input type="checkbox" id="KM_used" name="columns[]" value="KM_used">
                                                                <label for="KM_used">KM Used</label><br>
                                                                <input type="checkbox" id="RFID_Easy" name="columns[]" value="RFID_Easy">
                                                                <label for="RFID_Easy">RFID Easy</label><br>
                                                                <input type="checkbox" id="RFID_Auto" name="columns[]" value="RFID_Auto">
                                                                <label for="RFID_Auto">RFID Auto</label><br>
                                                                <input type="checkbox" id="oil_used" name="columns[]" value="oil_used">
                                                                <label for="oil_used">Oil Used</label><br>
                                                            </div>
                                                        </div>
                                                        <div style="text-align: center;">
                                                            <label for="months" class="form-label">Months</label><br>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="checkbox" name="months[]" value="01"> January<br>
                                                                <input type="checkbox" name="months[]" value="02"> February<br>
                                                                <input type="checkbox" name="months[]" value="03"> March<br>
                                                                <input type="checkbox" name="months[]" value="04"> April<br>
                                                                <input type="checkbox" name="months[]" value="05"> May<br>
                                                                <input type="checkbox" name="months[]" value="06"> June<br>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="checkbox" name="months[]" value="07"> July<br>
                                                            <input type="checkbox" name="months[]" value="08"> August<br>
                                                            <input type="checkbox" name="months[]" value="09"> September<br>
                                                            <input type="checkbox" name="months[]" value="10"> October<br>
                                                            <input type="checkbox" name="months[]" value="11"> November<br>
                                                            <input type="checkbox" name="months[]" value="12"> December<br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="year" class="form-label">Year</label>
                                                        <input type="number" id="year" name="year" class="form-control" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="format" class="form-label">Export Format</label>
                                                        <select id="format" name="format" class="form-control" required>
                                                            <option value="pdf">PDF</option>
                                                            <option value="word">Word</option>
                                                            <option value="excel">Excel</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" form="exportForm" class="btn btn-primary">Export</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Trip Ticket List Table-->
                            <div class="card shadow" style="margin-top: 30px">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 fw-bold">Trip Ticket List Info</p>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                        <div class="col-md-6 text-nowrap">
                                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                                <label class="form-label">Show&nbsp;
                                                    <select id="rowLimit" class="d-inline-block form-select form-select-sm">
                                                        <option value="5" selected="">5</option>
                                                        <option value="10">10</option>
                                                        <option value="15">15</option>
                                                        <option value="20">20</option>
                                                        <option value="25">25</option>
                                                        <option value="30">30</option>
                                                        <option value="40">40</option>
                                                        <option value="50">50</option>
                                                    </select>&nbsp;
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive table mt-2" id="dataTable">
                                        <table class="table my-0" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Plate Number</th>
                                                    <th>Gas Tank</th>
                                                    <th>Purchased Gas</th>
                                                    <th>Total Gas</th>
                                                    <th>Start Odometer</th>
                                                    <th>End Odometer</th>
                                                    <th>KM Used</th>
                                                    <th>RFID Easy</th>
                                                    <th>RFID Auto</th>
                                                    <th>Oil Used</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="tripTicketBody">
                                                <?php
                                                    // Check if there are results and output the data
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . htmlspecialchars($row['trip_ticket_date']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['plate_num']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['gas_tank']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['purchased_gas']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['start_odometer']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['end_odometer']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['KM_used']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['RFID_Easy']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['RFID_Auto']) . "</td>";
                                                            echo "<td>" . htmlspecialchars($row['oil_used']) . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='12'>No data found</td></tr>";
                                                    }

                                                    // Close the connection
                                                    $conn->close();
                                                    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 align-self-center">
                                            <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 27</p>
                                        </div>
                                        <div class="col-md-6">
                                            <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                                <ul class="pagination">
                                                    <li class="page-item disabled"><a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a></li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item"><a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a></li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
                <footer class="bg-white sticky-footer">
                    <div class="container my-auto">
                        <div class="text-center my-auto copyright"><span>Copyright © Gearchanix 2024</span></div>
                    </div>
                </footer>
            </div>
        </div>
        
        <!--Scripts-->
        <script src="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/js/rowlimit_triptix.js"></script>
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/script.js"></script>
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/bs-init.js"></script>
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/theme.js"></script> 
        <script src="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/js/scroll.js"></script>    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    </body>
</html>