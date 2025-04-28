<?php
include 'admin-header.php';
include '.vscode/config.php';

// Establish database connection
$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get user ID from URL parameter
$user_id = isset($_GET['id']) ? $con->real_escape_string($_GET['id']) : null;

// Initialize error message
$error_message = '';
$user_data = null;

if ($user_id) {
    // Fetch user details
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_object();
        $result->free();
    } else {
        $error_message = "User not found";
    }
} else {
    $error_message = "No user ID specified";
}

// Determine gender display if user exists
$genderDisplay = 'Not specified';
$genderClass = '';
if ($user_data && isset($user_data->gender)) {
    $genderUpper = strtoupper(trim($user_data->gender));
    if ($genderUpper === 'M') {
        $genderClass = 'gender-male';
        $genderDisplay = 'Male';
    } elseif ($genderUpper === 'F') {
        $genderClass = 'gender-female';
        $genderDisplay = 'Female';
    }
}

// Set default profile picture path
$defaultImage = 'assets/img/default.jpg';
$profilePicture = $defaultImage;
if ($user_data && isset($user_data->photo) && !empty($user_data->photo)) {
    // Check if the stored path already includes 'assets/img/'
    if (strpos($user_data->photo, 'assets/img/') === 0) {
        $profilePicture = $user_data->photo;
    } else {
        $profilePicture = 'assets/img/' . $user_data->photo;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $user_data ? "User Profile - " . htmlspecialchars($user_data->username) : "User Profile" ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .profile-details {
            padding: 20px;
        }
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: none;
        }
        .detail-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }
        .detail-item {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        .detail-value {
            font-weight: 500;
        }
        .gender-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }
        .gender-male {
            background-color: #d1e7ff;
            color: #0d6efd;
        }
        .gender-female {
            background-color: #ffd1e7;
            color: #d63384;
        }
        .back-btn {
            margin-bottom: 20px;
        }
        .empty-value {
            color: #6c757d;
            font-style: italic;
        }
        .error-message {
            color: #dc3545;
            padding: 20px;
            text-align: center;
            margin: 50px 0;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .profile-info {
            padding-left: 30px;
        }
        @media (max-width: 767px) {
            .profile-picture-container {
                text-align: center;
                margin-bottom: 20px;
            }
            .profile-info {
                padding-left: 15px;
                text-align: center;
            }
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
                        <p>User Details</p>
                        <h1><?= $user_data ? htmlspecialchars($user_data->username) : "User Profile" ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- user profile section -->
    <div class="mt-150 mb-150 animate__animated animate__fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <a href="admin-user.php" class="btn btn-outline-primary back-btn">
                        <i class="bi bi-arrow-left"></i> Back to Users List
                    </a>
                    
                    <?php if ($error_message): ?>
                        <div class="error-message">
                            <h3><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error_message) ?></h3>
                            <p>Please check the user ID and try again.</p>
                        </div>
                    <?php elseif ($user_data): ?>
                        <div class="profile-header">
                            <div class="row align-items-center">
                                <div class="col-md-2 profile-picture-container">
                                    <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" class="profile-picture" onerror="this.src='<?= $defaultImage ?>'">
                                </div>
                                <div class="col-md-10 profile-info">
                                    <h2><?= htmlspecialchars($user_data->username) ?></h2>
                                    <p class="text-muted mb-1">User ID: <?= htmlspecialchars($user_data->user_id) ?></p>
                                    
                                    <span class="gender-badge <?= $genderClass ?>">
                                        <?= $genderDisplay ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <div class="card detail-card">
                                    <div class="card-header detail-card-header">
                                        <i class="bi bi-person me-2"></i>Personal Information
                                    </div>
                                    <div class="card-body">
                                        <div class="detail-item">
                                            <div class="row">
                                                <div class="col-sm-4 detail-label">Username:</div>
                                                <div class="col-sm-8 detail-value"><?= htmlspecialchars($user_data->username) ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="row">
                                                <div class="col-sm-4 detail-label">Gender:</div>
                                                <div class="col-sm-8 detail-value">
                                                    <span class="gender-badge <?= $genderClass ?>">
                                                        <?= $genderDisplay ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <div class="card detail-card">
                                    <div class="card-header detail-card-header">
                                        <i class="bi bi-envelope me-2"></i>Contact Information
                                    </div>
                                    <div class="card-body">
                                        <div class="detail-item">
                                            <div class="row">
                                                <div class="col-sm-4 detail-label">Email:</div>
                                                <div class="col-sm-8 detail-value"><?= htmlspecialchars($user_data->email) ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="row">
                                                <div class="col-sm-4 detail-label">Phone:</div>
                                                <div class="col-sm-8 detail-value">
                                                    <?= !empty($user_data->phone) ? htmlspecialchars($user_data->phone) : '<span class="empty-value">Not provided</span>' ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="row">
                                                <div class="col-sm-4 detail-label">Address:</div>
                                                <div class="col-sm-8 detail-value">
                                                    <?= !empty($user_data->address) ? nl2br(htmlspecialchars($user_data->address)) : '<span class="empty-value">Not provided</span>' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end user profile section -->

    <?php include 'footer.php'; ?>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection
$con->close();
?>