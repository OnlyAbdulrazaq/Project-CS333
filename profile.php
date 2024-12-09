<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    try {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['error'] = "Invalid email format.";
        } else {
            if ($password) {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $email, $password, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $_SESSION['user_id']]);
            }

            if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "uploads/";
                $targetFile = $targetDir . uniqid() . "_" . basename($_FILES["profile_picture"]["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                        $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                        $stmt->execute([$targetFile, $_SESSION['user_id']]);
                        $response['profile_picture'] = $targetFile;
                    }
                }
            }

            $response['success'] = "Profile updated successfully!";
        }
    } catch (Exception $e) {
        $response['error'] = "Failed to update profile: " . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #bdc3c7; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card-container {
            background-color: #ffffff;
            border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); 
            max-width: 600px; 
            padding: 20px;
        }

        .card-header {
            background-color: #007b8e; 
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
            padding: 15px;
        }

        .card-header h4 {
            font-size: 20px;
            margin: 0;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px auto 15px; 
            display: block;
            border: 3px solid #007b8e; 
            background-color: #e9ecef;
            position: relative;
        }

        .profile-picture::before {
            content: ""; 
            color: transparent; 
            font-size: 14px; 
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-control {
            font-size: 14px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .btn-custom {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .btn-update {
            background-color: #007b8e;
            color: white;
            border: none;
        }

        .btn-update:hover {
            background-color: #005f6b; 
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
        }

        .btn-logout:hover {
            background-color: #b02a37;
        }

        label {
            font-size: 14px;
            color: #6c757d;
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-header">
            <h4>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h4>
        </div>
        <div class="card-body">
            <div class="text-center">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <div class="profile-picture"></div>
                <?php endif; ?>
            </div>
            <form id="profile-form" enctype="multipart/form-data">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <label for="password">New Password (اختياري)</label>
                <input type="password" id="password" name="password" class="form-control">
                
                <label for="profile_picture">Profile Picture (اختياري)</label>
                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                
                <button type="submit" class="btn btn-custom btn-update">Update Profile</button>
                <button type="button" onclick="window.location.href='logout.php'" class="btn btn-custom btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('profile-form');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(form);

            fetch('profile.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        });
    </script>
</body>
</html>