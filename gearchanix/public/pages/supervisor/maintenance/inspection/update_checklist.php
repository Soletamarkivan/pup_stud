<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'gearchanix';
$username = 'root';
$password = ''; // Change this if you have a password

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use isset to check if POST variables are set
    $pdi_id = isset($_POST['pdi_id']) ? $_POST['pdi_id'] : null;
    $battery = isset($_POST['battery']) ? $_POST['battery'] : 0;
    $lights = isset($_POST['lights']) ? $_POST['lights'] : 0;
    $oil_level = isset($_POST['oil_level']) ? $_POST['oil_level'] : 0;
    $water_level = isset($_POST['water_level']) ? $_POST['water_level'] : 0;
    $brakes = isset($_POST['brakes']) ? $_POST['brakes'] : 0;
    $air_pressure = isset($_POST['air_pressure']) ? $_POST['air_pressure'] : 0;
    $gas = isset($_POST['gas']) ? $_POST['gas'] : 0;
    $electronic = isset($_POST['electronic']) ? $_POST['electronic'] : 0;
    $tools = isset($_POST['tools']) ? $_POST['tools'] : 0;
    $self = isset($_POST['self']) ? $_POST['self'] : 0;

    // Check status based on the checklist
    $status = ($battery && $lights && $oil_level && $water_level && $brakes && 
               $air_pressure && $gas && $electronic && $tools && $self) ? 'approved' : 'unavailable';

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE pdi_form SET 
        battery = :battery,
        lights = :lights,
        oil_level = :oil_level,
        water_level = :water_level,
        brakes = :brakes,
        air_pressure = :air_pressure,
        gas = :gas,
        electronic = :electronic,
        tools = :tools,
        self = :self,
        status = :status,
        remarks = CASE 
            WHEN :status = 'approved' THEN 'Good Condition' 
            ELSE remarks 
        END
        WHERE pdi_ID = :pdi_id");

    // Bind parameters
    $stmt->bindParam(':pdi_id', $pdi_id);
    $stmt->bindParam(':battery', $battery);
    $stmt->bindParam(':lights', $lights);
    $stmt->bindParam(':oil_level', $oil_level);
    $stmt->bindParam(':water_level', $water_level);
    $stmt->bindParam(':brakes', $brakes);
    $stmt->bindParam(':air_pressure', $air_pressure);
    $stmt->bindParam(':gas', $gas);
    $stmt->bindParam(':electronic', $electronic);
    $stmt->bindParam(':tools', $tools);
    $stmt->bindParam(':self', $self);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
