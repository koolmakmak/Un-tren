<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once 'connect.php';

// ===== AUTO-UPDATE EXPIRED TICKETS =====
// Get the EXPIRED status ID
$expired_status_query = $conn->query("SELECT status_id FROM booking_status WHERE status_code = 'EXPIRED'");
$expired_status_row = $expired_status_query->fetch_assoc();
$expired_status_id = $expired_status_row['status_id'];

// Get the CANCELLED status ID (we don't want to change cancelled tickets)
$cancelled_status_query = $conn->query("SELECT status_id FROM booking_status WHERE status_code = 'CANCELLED'");
$cancelled_status_row = $cancelled_status_query->fetch_assoc();
$cancelled_status_id = $cancelled_status_row['status_id'];

// Update expired bookings automatically
// This checks if the service date has passed and the booking is not already cancelled or expired
$update_expired = $conn->prepare("
    UPDATE booking b
    INNER JOIN service_runs sr ON b.train_id = sr.id
    SET b.status_id = ?
    WHERE sr.service_date < CURDATE()
    AND b.status_id != ?
    AND b.status_id != ?
");
$update_expired->bind_param("iii", $expired_status_id, $cancelled_status_id, $expired_status_id);
$update_expired->execute();
$expired_count = $update_expired->affected_rows;

if ($expired_count > 0) {
    $message = "$expired_count ticket(s) automatically updated to EXPIRED status.";
}

// Handle various actions
$action = $_GET['action'] ?? '';
$message = '';

// Delete Booking
if ($action === 'delete_booking' && isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $conn->query("DELETE FROM booking_seats WHERE book_id = $book_id");
    $conn->query("DELETE FROM booking WHERE book_id = $book_id");
    $message = "Booking deleted successfully!";
}

// Update Booking Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking_status'])) {
    $book_id = $_POST['book_id'];
    $status_id = $_POST['status_id'];
    $stmt = $conn->prepare("UPDATE booking SET status_id = ? WHERE book_id = ?");
    $stmt->bind_param("ii", $status_id, $book_id);
    $stmt->execute();
    $message = "Booking status updated!";
}

// Delete Train Service
if ($action === 'delete_train_service' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM train_services WHERE id = $id");
    $message = "Train service deleted!";
}

// Create/Update Train Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_train_service'])) {
    $id = $_POST['train_service_id'] ?? null;
    $name = $_POST['name'];
    $service_type_id = $_POST['service_type_id'];
    $route_id = $_POST['route_id'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE train_services SET name = ?, service_type_id = ?, route_id = ? WHERE id = ?");
        $stmt->bind_param("siii", $name, $service_type_id, $route_id, $id);
        $message = "Train service updated!";
    } else {
        $stmt = $conn->prepare("INSERT INTO train_services (name, service_type_id, route_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $name, $service_type_id, $route_id);
        $message = "Train service created!";
    }
    $stmt->execute();
}

// Delete Service Run
if ($action === 'delete_service_run' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM service_runs WHERE id = $id");
    $message = "Service run deleted!";
}

// Create/Update Service Run
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service_run'])) {
    $id = $_POST['service_run_id'] ?? null;
    $train_service_id = $_POST['train_service_id'];
    $service_date = $_POST['service_date'];
    $sequence_no = $_POST['sequence_no'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE service_runs SET train_service_id = ?, service_date = ?, sequence_no = ? WHERE id = ?");
        $stmt->bind_param("isii", $train_service_id, $service_date, $sequence_no, $id);
        $message = "Service run updated!";
    } else {
        $stmt = $conn->prepare("INSERT INTO service_runs (train_service_id, service_date, sequence_no) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $train_service_id, $service_date, $sequence_no);
        $message = "Service run created!";
    }
    $stmt->execute();
}

// Delete Service Type
if ($action === 'delete_service_type' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM services WHERE id = $id");
    $message = "Service type deleted!";
}

// Create/Update Service Type
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service_type'])) {
    $id = $_POST['service_type_id'] ?? null;
    $name = $_POST['name'];
    $headcode_digit = $_POST['headcode_digit'];
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE services SET name = ?, headcode_digit = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $headcode_digit, $id);
        $message = "Service type updated!";
    } else {
        $stmt = $conn->prepare("INSERT INTO services (name, headcode_digit) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $headcode_digit);
        $message = "Service type created!";
    }
    $stmt->execute();
}

// Fetch all data
$bookings = $conn->query("
    SELECT b.*, u.username, bs_status.status_code,
           GROUP_CONCAT(bs.seat_number ORDER BY bs.seat_number SEPARATOR ', ') as seats,
           sr.service_date,
           CASE 
               WHEN sr.service_date < CURDATE() THEN 1 
               ELSE 0 
           END as is_expired
    FROM booking b
    LEFT JOIN users_info u ON b.user_id = u.user_id
    LEFT JOIN booking_status bs_status ON b.status_id = bs_status.status_id
    LEFT JOIN booking_seats bs ON b.book_id = bs.book_id
    LEFT JOIN service_runs sr ON b.train_id = sr.id
    GROUP BY b.book_id
    ORDER BY b.booking_date DESC
");

$train_services = $conn->query("
    SELECT ts.*, s.name as service_type, r.route_name
    FROM train_services ts
    LEFT JOIN services s ON ts.service_type_id = s.id
    LEFT JOIN routes r ON ts.route_id = r.route_id
    ORDER BY ts.id DESC
");

$service_runs = $conn->query("
    SELECT sr.*, ts.name as train_service_name
    FROM service_runs sr
    LEFT JOIN train_services ts ON sr.train_service_id = ts.id
    ORDER BY sr.service_date DESC, sr.id DESC
");

$users = $conn->query("
    SELECT user_id, username, firstname, lastname, email
    FROM users_info
    ORDER BY user_id DESC
");

$service_types = $conn->query("
    SELECT * FROM services ORDER BY id DESC
");

$booking_statuses = $conn->query("SELECT * FROM booking_status");
$routes = $conn->query("SELECT * FROM routes");
$all_services = $conn->query("SELECT * FROM services");
$all_train_services = $conn->query("SELECT * FROM train_services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Train Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
        }

        header {
            background: #720A00;
            color: white;
            padding: 15px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 55px;
            margin-right: 12px;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .message {
            background: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: <?= $message ? 'block' : 'none' ?>;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 24px;
            background: white;
            border: 2px solid #720A00;
            color: #720A00;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .tab.active {
            background: #720A00;
            color: white;
        }

        .tab-content {
            display: none;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #720A00;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #2196F3;
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-primary {
            background: #720A00;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-approved {
            background: #4CAF50;
            color: white;
        }

        .status-cancelled {
            background: #f44336;
            color: white;
        }

        .status-expired {
            background: #9E9E9E;
            color: white;
        }

        .expired-row {
            background-color: #f5f5f5;
            opacity: 0.7;
        }
    </style>
</head>
<body>

<header>
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div class="logo">
        <img src="assets/train.png" alt="Train Logo">
        <h1>Admin Dashboard - UTR Train Reservation System</h1>
        </div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <span style="font-size: 14px;">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="login.php?logout=1" style="background: white; color: #720A00; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600;">Logout</a>
        </div>
    </div>
</header>

<div class="container">
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="tabs">
        <div class="tab active" onclick="switchTab('bookings')">📋 Bookings</div>
        <div class="tab" onclick="switchTab('train_services')">🚆 Train Services</div>
        <div class="tab" onclick="switchTab('service_runs')">📅 Service Runs</div>
        <div class="tab" onclick="switchTab('service_types')">🎫 Service Types</div>
        <div class="tab" onclick="switchTab('users')">👥 Users</div>
    </div>

    <!-- BOOKINGS TAB -->
    <div id="bookings" class="tab-content active">
        <h2>All Bookings</h2>
        <?php if ($expired_count > 0): ?>
            <div style="background: #FF9800; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                ⚠️ <?= $expired_count ?> ticket(s) were automatically updated to EXPIRED status.
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Code</th>
                    <th>User</th>
                    <th>Train ID</th>
                    <th>Seats</th>
                    <th>Service Date</th>
                    <th>Status</th>
                    <th>Booking Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                <tr class="<?= $row['is_expired'] ? 'expired-row' : '' ?>">
                    <td>#<?= $row['book_id'] ?></td>
                    <td><strong><?= $row['booking_code'] ?></strong></td>
                    <td><?= htmlspecialchars($row['username'] ?? 'N/A') ?></td>
                    <td>Train <?= $row['train_id'] ?></td>
                    <td><?= htmlspecialchars($row['seats']) ?></td>
                    <td>
                        <?= $row['service_date'] ? date('M j, Y', strtotime($row['service_date'])) : 'N/A' ?>
                        <?php if ($row['is_expired']): ?>
                            <span style="color: red; font-weight: bold;"> (Past)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?= strtolower($row['status_code']) ?>">
                            <?= $row['status_code'] ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y H:i', strtotime($row['booking_date'])) ?></td>
                    <td>
                        <?php if ($row['status_code'] !== 'EXPIRED'): ?>
                            <button class="btn btn-edit" onclick="editBooking(<?= $row['book_id'] ?>, <?= $row['status_id'] ?>)">Edit Status</button>
                        <?php endif; ?>
                        <a href="?action=delete_booking&id=<?= $row['book_id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Delete this booking?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- TRAIN SERVICES TAB -->
    <div id="train_services" class="tab-content">
        <h2>Train Services</h2>
        <button class="btn btn-primary" onclick="openTrainServiceModal()">+ Add New Train Service</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Service Type</th>
                    <th>Route</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $train_services->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['service_type']) ?></td>
                    <td><?= htmlspecialchars($row['route_name']) ?></td>
                    <td>
                        <button class="btn btn-edit" onclick='editTrainService(<?= json_encode($row) ?>)'>Edit</button>
                        <a href="?action=delete_train_service&id=<?= $row['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Delete this train service?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- SERVICE RUNS TAB -->
    <div id="service_runs" class="tab-content">
        <h2>Service Runs</h2>
        <button class="btn btn-primary" onclick="openServiceRunModal()">+ Add New Service Run</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Train Service</th>
                    <th>Service Date</th>
                    <th>Sequence No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $service_runs->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['train_service_name']) ?></td>
                    <td><?= date('M j, Y', strtotime($row['service_date'])) ?></td>
                    <td><?= $row['sequence_no'] ?></td>
                    <td>
                        <button class="btn btn-edit" onclick='editServiceRun(<?= json_encode($row) ?>)'>Edit</button>
                        <a href="?action=delete_service_run&id=<?= $row['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Delete this service run?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- SERVICE TYPES TAB -->
    <div id="service_types" class="tab-content">
        <h2>Service Types</h2>
        <button class="btn btn-primary" onclick="openServiceTypeModal()">+ Add New Service Type</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Headcode Digit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $service_types->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['headcode_digit']) ?></td>
                    <td>
                        <button class="btn btn-edit" onclick='editServiceType(<?= json_encode($row) ?>)'>Edit</button>
                        <a href="?action=delete_service_type&id=<?= $row['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Delete this service type?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- USERS TAB -->
    <div id="users" class="tab-content">
        <h2>All Users</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['firstname']) ?></td>
                    <td><?= htmlspecialchars($row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODALS -->

<!-- Edit Booking Status Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Booking Status</h2>
            <span class="close" onclick="closeModal('bookingModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="book_id" id="edit_book_id">
            <div class="form-group">
                <label>Status</label>
                <select name="status_id" required>
                    <?php
                    $booking_statuses->data_seek(0);
                    while ($status = $booking_statuses->fetch_assoc()):
                    ?>
                        <option value="<?= $status['status_id'] ?>"><?= $status['status_code'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="update_booking_status" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</div>

<!-- Train Service Modal -->
<div id="trainServiceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="trainServiceModalTitle">Add Train Service</h2>
            <span class="close" onclick="closeModal('trainServiceModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="train_service_id" id="train_service_id">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" id="ts_name" required>
            </div>
            <div class="form-group">
                <label>Service Type</label>
                <select name="service_type_id" id="ts_service_type_id" required>
                    <?php
                    $all_services->data_seek(0);
                    while ($service = $all_services->fetch_assoc()):
                    ?>
                        <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Route</label>
                <select name="route_id" id="ts_route_id" required>
                    <?php
                    $routes->data_seek(0);
                    while ($route = $routes->fetch_assoc()):
                    ?>
                        <option value="<?= $route['id'] ?>"><?= $route['route_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="save_train_service" class="btn btn-primary">Save Train Service</button>
        </form>
    </div>
</div>

<!-- Service Run Modal -->
<div id="serviceRunModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="serviceRunModalTitle">Add Service Run</h2>
            <span class="close" onclick="closeModal('serviceRunModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="service_run_id" id="service_run_id">
            <div class="form-group">
                <label>Train Service</label>
                <select name="train_service_id" id="sr_train_service_id" required>
                    <?php
                    $all_train_services->data_seek(0);
                    while ($ts = $all_train_services->fetch_assoc()):
                    ?>
                        <option value="<?= $ts['id'] ?>"><?= $ts['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Service Date</label>
                <input type="date" name="service_date" id="sr_service_date" required>
            </div>
            <div class="form-group">
                <label>Sequence Number</label>
                <input type="number" name="sequence_no" id="sr_sequence_no" required>
            </div>
            <button type="submit" name="save_service_run" class="btn btn-primary">Save Service Run</button>
        </form>
    </div>
</div>

<!-- Service Type Modal -->
<div id="serviceTypeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="serviceTypeModalTitle">Add Service Type</h2>
            <span class="close" onclick="closeModal('serviceTypeModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="service_type_id" id="service_type_id">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" id="st_name" required>
            </div>
            <div class="form-group">
                <label>Headcode Digit</label>
                <input type="text" name="headcode_digit" id="st_headcode_digit" maxlength="1" required>
            </div>
            <button type="submit" name="save_service_type" class="btn btn-primary">Save Service Type</button>
        </form>
    </div>
</div>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById(tabName).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function editBooking(bookId, statusId) {
    document.getElementById('edit_book_id').value = bookId;
    document.querySelector('[name="status_id"]').value = statusId;
    document.getElementById('bookingModal').classList.add('active');
}

function openTrainServiceModal() {
    document.getElementById('trainServiceModalTitle').textContent = 'Add Train Service';
    document.getElementById('train_service_id').value = '';
    document.getElementById('ts_name').value = '';
    document.getElementById('trainServiceModal').classList.add('active');
}

function editTrainService(data) {
    document.getElementById('trainServiceModalTitle').textContent = 'Edit Train Service';
    document.getElementById('train_service_id').value = data.id;
    document.getElementById('ts_name').value = data.name;
    document.getElementById('ts_service_type_id').value = data.service_type_id;
    document.getElementById('ts_route_id').value = data.route_id;
    document.getElementById('trainServiceModal').classList.add('active');
}

function openServiceRunModal() {
    document.getElementById('serviceRunModalTitle').textContent = 'Add Service Run';
    document.getElementById('service_run_id').value = '';
    document.getElementById('sr_service_date').value = '';
    document.getElementById('sr_sequence_no').value = '';
    document.getElementById('serviceRunModal').classList.add('active');
}

function editServiceRun(data) {
    document.getElementById('serviceRunModalTitle').textContent = 'Edit Service Run';
    document.getElementById('service_run_id').value = data.id;
    document.getElementById('sr_train_service_id').value = data.train_service_id;
    document.getElementById('sr_service_date').value = data.service_date;
    document.getElementById('sr_sequence_no').value = data.sequence_no;
    document.getElementById('serviceRunModal').classList.add('active');
}

function openServiceTypeModal() {
    document.getElementById('serviceTypeModalTitle').textContent = 'Add Service Type';
    document.getElementById('service_type_id').value = '';
    document.getElementById('st_name').value = '';
    document.getElementById('st_headcode_digit').value = '';
    document.getElementById('serviceTypeModal').classList.add('active');
}

function editServiceType(data) {
    document.getElementById('serviceTypeModalTitle').textContent = 'Edit Service Type';
    document.getElementById('service_type_id').value = data.id;
    document.getElementById('st_name').value = data.name;
    document.getElementById('st_headcode_digit').value = data.headcode_digit;
    document.getElementById('serviceTypeModal').classList.add('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}
</script>

</body>
</html>