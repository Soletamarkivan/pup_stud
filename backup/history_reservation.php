<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        
        <!-- Links -->
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/css/styles.css">
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/Gearchanix/GEARCHANIX-main/gearchanix/src/assets/css/search.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <title>Vehicle Reservation History</title>
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
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/dispatcher.html"><i class="fas fa-tachometer-alt"></i><span>DASHBOARD</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/dispatcher-vehiclelist.html"><i class="fas fa-list"></i><span>VEHICLE LIST</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/vehicle-reservation.html"><i class="fas fa-table"></i><span>VEHICLE RESERVATION</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/trip-ticket.html"><i class="fas fa-bus"></i><span>TRIP TICKET</span></a></li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/dispatcher-history.html" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                                <i class="fas fa-history"></i><span>HISTORY</span>
                            </a>
                            <div id="collapseHistory" class="collapse" aria-labelledby="headingHistory" data-bs-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/history_reservation.html"><i class="fas fa-table"></i> VEHICLE RESERVATION</a>
                                    <a class="collapse-item" href="/Gearchanix/GEARCHANIX-MAIN/gearchanix/public/pages/dispatcher/history_trip.html"><i class="fas fa-bus"></i> VEHICLE TRIP TICKET</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!--Content Wrapper-->
            <div class="d-flex flex-column" id="content-wrapper">
                <div id="content">
                    <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                        <div class="container-fluid">
                            <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                            <!--Search Function-->
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
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                            
                                            <!--Notifications-->
                                            <h6 class="dropdown-header">Notifications</h6>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                                </div>

                                                <!--Notif 1-->
                                                <div><span class="small text-gray-500">September 11, 2024</span>
                                                    <p>A new monthly report is ready to download!</p>
                                                </div>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                                </div>

                                                <!--Notif 2-->
                                                <div><span class="small text-gray-500">Septermber 12, 2024</span>
                                                    <p>13 Vehicles are unaivalable for maintenance</p>
                                                </div>
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                                </div>

                                                <!--Notif 3-->
                                                <div><span class="small text-gray-500">October 5, 2024</span>
                                                    <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                                                </div>
                                            </a>
                                            
                                            <!--Function to show all notifications-->
                                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
                                        </div>
                                    </div>
                                </li>
                                <!--Profile-->
                                <div class="d-none d-sm-block topbar-divider"></div>
                                <li class="nav-item dropdown no-arrow">
                                    <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Dispatcher</span><img class="border rounded-circle img-profile" src="assets/img/avatars/example.jpg"></a>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                            <a class="dropdown-item" href="/Gearchanix/GEARCHANIX-main/gearchanix/public/pages/dispatcher/profile-dispatcher.html"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                            <a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a>
                                            <a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
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
                                <h3 class="text-dark mb-0">Vehicle Reservation History</h3>
                            </div>
                            <div class="row align-items-center">
                                <!-- Export Modal -->
                                <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exportModalLabel">Select Month and Year</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="exportForm" action="export.php" method="GET">
                                                    <div class="mb-3">
                                                        <label for="month" class="form-label">Month</label>
                                                        <select id="month" name="month" class="form-control">
                                                            <option value="01">January</option>
                                                            <option value="02">February</option>
                                                            <option value="03">March</option>
                                                            <option value="04">April</option>
                                                            <option value="05">May</option>
                                                            <option value="06">June</option>
                                                            <option value="07">July</option>
                                                            <option value="08">August</option>
                                                            <option value="09">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="year" class="form-label">Year</label>
                                                        <input type="number" id="year" name="year" class="form-control" value="<?php echo date('Y'); ?>" min="2000" max="<?php echo date('Y'); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="format" class="form-label">Export Format</label>
                                                        <select id="format" name="format" class="form-control">
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
                                <!-- Search bar --> 
                                <div class="col d-flex justify-content-center">
                                    <form class="navbar-search" onsubmit="return false;"> <!-- Prevent form submission -->
                                        <div class="input-group">
                                            <input id="searchInput" class="form-control search-input" type="text" placeholder="Search">
                                            <button class="btn btn-primary py-0" type="button" onclick="searchFunction()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Export and action buttons -->
                                <div class="col-auto d-flex align-items-center justify-content-end">
                                    <button class="btn btn-light btn-sm mx-1" id="addBtn" title="Add"><i class="fas fa-plus"></i></button>
                                    <a class="btn btn-primary btn-sm ms-3" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</a>
                                </div>
                            </div>
                            <!-- Reservation table -->
                            <div class="card shadow" style="margin-top: 30px">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 fw-bold">Reservation History Info</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-nowrap">
                                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                                <label class="form-label">Show&nbsp;
                                                    <select class="d-inline-block form-select form-select-sm">
                                                        <option value="10" selected="">10</option>
                                                        <option value="25">15</option>
                                                        <option value="50">20</option>
                                                        <option value="50">25</option>
                                                        <option value="100">30</option>
                                                        <option value="100">40</option>
                                                        <option value="100">50</option>
                                                    </select>&nbsp;
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive table mt-2" id="dataTable">
                                    <table class="table my-0">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Type of Vehicle</th>
                                                <th>Scheduled Date</th>
                                                <th>Destination</th>
                                                <th>Duration</th>
                                                <th>Time of Departure</th>
                                                <th>No. Of Passengers</th>
                                                <th>Office or Department</th>
                                                <th>Email</th>
                                                <th>Contact No.</th>
                                                <th>Service Type</th>
                                                <th>Purpose</th>
                                                <th>Passenger Manifest</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reservation-data">
                                            
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>            
                            <!-- Delete Confirmation Modal  -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the selected reservation?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Success Modal for Deletion -->
                            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Reservation deleted successfully.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
                <footer>
                    <div class="container my-auto">
                        <div class="text-center my-auto copyright"><span>Copyright © Gearchanix 2024</span></div>
                    </div>
                </footer>
            </div>
        </div>

        <!--Scripts-->
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/bs-init.js"></script>
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/history_reservation.js"></script>
        <script src="/Gearchanix/GEARCHANIX-MAIN/gearchanix/src/assets/js/theme.js"></script>   
        <script src="/GEARCHANIX-main/gearchanix/src/assets/js/scroll.js"></script>  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    </body>
</html>
