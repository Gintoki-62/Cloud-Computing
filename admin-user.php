<?php
$header = array(
    "user_id" => "User ID",
    "username" => "Username",
    "gender" => "Gender",
    "email" => "Email",
    "phone" => "Phone",
    "address" => "Address",
    "action" => "Actions"
);

include 'headerr.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle search functionality
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$sql = "SELECT * FROM users"; 
if (!empty($search_query)) {
    $search_query = $con->real_escape_string($search_query);
    $sql .= " WHERE username LIKE '%$search_query%' OR email LIKE '%$search_query%' OR user_id LIKE '%$search_query%'";
}

// Handle single user lookup
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["user_id"])) {
    $user_id = $con->real_escape_string(trim($_POST["user_id"]));
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
}

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .user-avatar {
            max-width: 60px;
            height: auto;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }
        .user-avatar:hover {
            transform: scale(1.1);
        }
        .text-danger {
            color: #dc3545;
        }
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(248, 249, 250, 0.7);
            transform: translateX(2px);
        }
        .action-buttons .btn {
            margin: 2px;
            min-width: 70px;
        }
        .search-box {
            max-width: 400px;
            margin: 0 auto 20px;
        }
        .breadcrumb-section {
            margin-bottom: 50px;
        }
        .user-details-wrap {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .table th {
            font-weight: 600;
            border-top: none;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6;
        }
        .alert-no-users {
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        /* Gender styles */
        .gender-male {
            background-color: #d1e7ff;
            color: #0d6efd;
        }
        .gender-female {
            background-color: #ffd1e7;
            color: #d63384;
        }
        .gender-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }
        .email-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text animate__animated animate__fadeInDown">
                        <p>Admin Panel</p>
                        <h1>User Details</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- user management section -->
    <div class="mt-150 mb-150 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="user-details-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0"><i class="bi bi-people me-2"></i>USER DETAILS</h3>
                        </div>
                        
                        <!-- Search form -->
                        <div class="search-box animate__animated animate__fadeIn">
                            <form action="" method="GET" class="mb-4">
                                <div class="input-group shadow-sm">
                                    <input type="search" name="search_query" class="form-control border-end-0" 
                                           placeholder="Search users by name, email or ID..." 
                                           value="<?= htmlspecialchars($search_query) ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <?php if ($result->num_rows > 0) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <?php foreach ($header as $key => $value) : ?>
                                            <th><?= $value ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_object()) : 
                                        // Determine gender class and display text
                                        $genderClass = '';
                                        $genderDisplay = '';
                                        if ($row->gender) {
                                            $genderUpper = strtoupper(trim($row->gender));
                                            if ($genderUpper === 'M') {
                                                $genderClass = 'gender-male';
                                                $genderDisplay = 'Male';
                                            } elseif ($genderUpper === 'F') {
                                                $genderClass = 'gender-female';
                                                $genderDisplay = 'Female';
                                            }
                                        }
                                    ?>
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="fw-bold"><?= htmlspecialchars($row->user_id) ?></td>
                                        <td><?= htmlspecialchars($row->username) ?></td>
                                        <td class="text-center">
                                            <?php if ($row->gender && !empty($genderDisplay)) : ?>
                                                <span class="gender-badge <?= $genderClass ?>">
                                                    <?= $genderDisplay ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="text-muted">Not specified</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="email-cell" title="<?= htmlspecialchars($row->email) ?>">
                                            <?= htmlspecialchars($row->email) ?>
                                        </td>
                                        <td><?= $row->phone ? htmlspecialchars($row->phone) : '<span class="text-muted">N/A</span>' ?></td>
                                        <td><?= $row->address ? htmlspecialchars($row->address) : '<span class="text-muted">N/A</span>' ?></td>
                                        <td class="text-right action-buttons">
                                            <a href="view-user.php?id=<?= $row->user_id ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else : ?>
                            <div class="alert alert-warning text-center alert-no-users animate__animated animate__fadeIn">
                                <i class="bi bi-exclamation-circle me-2"></i> No users found.
                                <?php if (!empty($search_query)) : ?>
                                    <a href="admin-user.php" class="alert-link ms-2">Clear search</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end user management section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection
if (isset($result)) {
    $result->free(); 
}
$con->close(); 
?>