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
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/css/action-buttons.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <title>Vehicle Trip Ticket</title>
    </head> 

    <body id="page-top">
        <div id="wrapper">
            <!-- Sidebar -->
            <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
                <div class="container-fluid d-flex flex-column p-0">
                    <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                        <div class="sidebar-brand-icon rotate-n-15"></div>
                        <div class="sidebar-brand-text mx-3"><span>GEARCHANIX</span></div>
                    </a>
                    <hr class="sidebar-divider my-0">
                    <ul class="navbar-nav text-light" id="accordionSidebar">
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/mechanic.html"><i class="fas fa-tachometer-alt"></i><span>DASHBOARD</span></a></li>

                        <!-- Vehicle Drop Down -->
                        <li class="nav-item">
                            <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicles" aria-expanded="false" aria-controls="collapseVehicles"><i class="fas fa-car"></i><span>VEHICLES</span></a>
                            <div id="collapseVehicles" class="collapse" aria-labelledby="headingVehicles" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/mechanic/vehicles/vehicle-list.html"><i class="fas fa-list"></i> VEHICLE LIST</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/vehicles/vehicle-history.html"><i class="fas fa-history"></i> VEHICLE HISTORY</a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Maintenance Drop Down -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMaintenance" aria-expanded="false" aria-controls="collapseMaintenance"><i class="fas fa-tools"></i><span>MAINTENANCE</span></a>
                            <div id="collapseMaintenance" class="collapse" aria-labelledby="headingMaintenance" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/maintenance/inspection/inspection.html"><i class="fas fa-check-circle"></i> INSPECTION</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/maintenance/service/service.html"><i class="fas fa-wrench"></i> SERVICE</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/maintenance/maintenance-request.html"><i class="fas fa-clipboard-list"></i> MAINTENANCE REQUEST</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/maintenance/service-maintenance/service-maintenance.html"><i class="fas fa-hammer"></i> SERVICE MAINTENANCE</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/documents/documents.html"><i class="fas fa-book-open"></i><span>DOCUMENTS</span></a></li>
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
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Mechanic</span><img class="border rounded-circle img-profile" src="assets/img/avatars/example.jpg"></a>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                            <a class="dropdown-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/mechanic/profile-mechanic.html"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/login-reg/logout.php">
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
                                    <form class="navbar-search">
                                        <div class="input-group">
                                            <input class="form-control search-input" type="text" placeholder="Search">
                                            <button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>                                                                      
                                </div> 
                                <!-- Export and action buttons -->
                                <div class="col-auto d-flex align-items-center justify-content-end">
                                    <button class="btn btn-light btn-sm mx-1" id="addBtn" title="Add" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                        <i class="fas fa-plus"></i>
                                    </button>                                    
                                    <a class="btn btn-primary btn-sm ms-3" role="button" data-bs-toggle="modal" data-bs-target="#exportModal">
                                        <i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report
                                    </a>
                                </div>
                                <!-- Export Modal -->
                                <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exportModalLabel">Select Columns, Month(s), and Year</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="exportForm" action="export_m_vehiclelist.php" method="GET">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Columns to Include</label><br>
                                                        <div class="row">
                                                            <!-- Column 1 -->
                                                            <div class="col-md-6">
                                                                <input type="checkbox" id="vehicle_name" name="columns[]" value="vehicle_name">
                                                                <label for="vehicle_name">Vehicle Name</label><br>
                                                                <input type="checkbox" id="vehicle_model" name="columns[]" value="vehicle_model">
                                                                <label for="vehicle_model">Vehicle Model</label><br>
                                                                <input type="checkbox" id="vehicle_year" name="columns[]" value="vehicle_year">
                                                                <label for="vehicle_year">Vehicle Year</label><br>
                                                                <input type="checkbox" id="vehicle_vin" name="columns[]" value="vehicle_vin">
                                                                <label for="vehicle_vin">Vehicle VIN</label><br>
                                                                <input type="checkbox" id="vehicle_type" name="columns[]" value="vehicle_type">
                                                                <label for="vehicle_type">Vehicle Type</label><br>
                                                                <input type="checkbox" id="plate_num" name="columns[]" value="plate_num">
                                                                <label for="plate_num">Plate Number</label><br>
                                                            </div>
                                                        
                                                            <!-- Column 2 -->
                                                            <div class="col-md-6">
                                                                <input type="checkbox" id="lifespan" name="columns[]" value="lifespan">
                                                                <label for="lifespan">Lifespan</label><br>
                                                                <input type="checkbox" id="current_meter" name="columns[]" value="current_meter">
                                                                <label for="current_meter">Current Meter</label><br>
                                                                <input type="checkbox" id="pms_date" name="columns[]" value="pms_date">
                                                                <label for="pms_date">PMS Date</label><br>
                                                                <input type="checkbox" id="ems_date" name="columns[]" value="ems_date">
                                                                <label for="ems_date">EMS Date</label><br>
                                                                <input type="checkbox" id="vehicle_remarks" name="columns[]" value="vehicle_remarks">
                                                                <label for="vehicle_remarks">Vehicle Remarks</label><br>
                                                                <input type="checkbox" id="vehicle_status" name="columns[]" value="vehicle_status">
                                                                <label for="vehicle_status">Vehicle Status</label><br>
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
                                                        <option value="0" selected="">Select</option>
                                                        <option value="5">5</option>
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