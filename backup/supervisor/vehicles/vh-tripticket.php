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
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicles" aria-expanded="false" aria-controls="collapseVehicles"><i class="fas fa-car"></i><span>VEHICLES</span></a>
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
                            <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ..."><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                            </form>
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
                                <li class="nav-item dropdown no-arrow mx-1">
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter"></span><i class="fas fa-bell fa-fw" style="font-size: 17px;"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                            <h6 class="dropdown-header">Notifications</h6><a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                                </div>
                                                <div><span class="small text-gray-500">December 12, 2019</span>
                                                    <p>A new monthly report is ready to download!</p>
                                                </div>
                                            </a><a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                                </div>
                                                <div><span class="small text-gray-500">December 7, 2019</span>
                                                    <p>$290.29 has been deposited into your account!</p>
                                                </div>
                                            </a><a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                                </div>
                                                <div><span class="small text-gray-500">December 2, 2019</span>
                                                    <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                                                </div>
                                            </a><a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                        </div>
                                    </div>
                                </li>
                                <div class="d-none d-sm-block topbar-divider"></div>
                                <li class="nav-item dropdown no-arrow">
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Chief TMPS</span><img class="border rounded-circle img-profile" src="assets/img/avatars/example.jpg"></a>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
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
                                            <input class="bg-light form-control border-0 small" type="text" id="searchInput" placeholder="Search" onkeyup="searchFunction()">
                                            <button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Export and action buttons -->
                                <div class="col-auto d-flex align-items-center justify-content-end">
                                    <button class="btn btn-light btn-sm mx-1" id="addBtn" title="Add"><i class="fas fa-plus"></i></button>
                                    <!-- <button class="btn btn-light btn-sm mx-1" id="editBtn" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-light btn-sm mx-1" id="deleteBtn" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash"></i></button> -->
                                    <a class="btn btn-primary btn-sm ms-3" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</a>
                                </div>
                            </div>
                            <!--Trip Ticket List Table-->
                            <div class="card shadow" style="margin-top: 30px">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 fw-bold">Trip Ticket List Info</p>
                                </div>
                                <div class="card-body">
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
                                </div>
                                <div class="card-body shadow-sm" style="text-align: center;display: inline-block;overflow: visible;--bs-danger: #9E0F12;--bs-danger-rgb: 158,15,18;">
                                    <div class="row">
                                        <div class="col-md-6 align-self-center">
                                            <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite"></p>
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
        <script src="/GEARCHANIX-MAIN/gearchanix/src/assets/js/script.js"></script>
        <script src="/GEARCHANIX-MAIN/gearchanix/src/assets/js/bs-init.js"></script>
        <script src="/GEARCHANIX-MAIN/gearchanix/src/assets/js/theme.js"></script> 
        <script src="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/js/scroll.js"></script>    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    </body>
</html>