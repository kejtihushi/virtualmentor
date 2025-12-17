<?php
session_start();
include('db.php'); // Lidhja me databazën

// Kontrollo nëse përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Gjej rolin për të ditur se në cilin dashboard të kthehemi
$dashboard_link = "#"; // default nëse nuk gjendet roli

if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'student') {
        $dashboard_link = "student_dashboard.php";
    } elseif ($_SESSION['user_role'] === 'professor') {
        $dashboard_link = "professor_dashboard.php";
    }
}

// Kontrolloni nëse është dërguar formulari për ndryshimin e fjalëkalimit
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kontrollo nëse fjalëkalimet përputhen
    if ($new_password == $confirm_password) {
        // Kontrollo nëse fjalëkalimi aktual është i saktë
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verifikoni nëse fjalëkalimi i vjetër është i saktë
        if (password_verify($current_password, $user['password'])) {
            // Krijo një fjalëkalim të ri dhe ruaj në databazë
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password_hashed, $user_id);

            if ($stmt->execute()) {
                $success_msg = "Fjalëkalimi u ndryshua me sukses!";
            } else {
                $error_msg = "Ka ndodhur një gabim gjatë ndryshimit të fjalëkalimit.";
            }
        } else {
            $error_msg = "Fjalëkalimi aktual është i gabuar.";
        }
    } else {
        $error_msg = "Fjalëkalimet e reja nuk përputhen.";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ndrysho Fjalëkalimin</title>
    <link rel="stylesheet" href="styles.css"> <!-- Sigurohuni që ky stil është i disponueshëm -->
</head>
<body>

<div class="change-password-container">
    <h2>Ndrysho Fjalëkalimin</h2>

    <?php if (isset($success_msg)) { echo "<p class='success-msg'>$success_msg</p>"; } ?>
    <?php if (isset($error_msg)) { echo "<p class='error-msg'>$error_msg</p>"; } ?>

    <form method="POST" action="">
        <label for="current_password">Fjalëkalimi aktual:</label>
        <input type="password" name="current_password" required><br>

        <label for="new_password">Fjalëkalimi i ri:</label>
        <input type="password" name="new_password" required><br>

        <label for="confirm_password">Konfirmo fjalëkalimin e ri:</label>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" name="change_password" value="Ndrysho Fjalëkalimin" class="submit-btn">
    </form>

    <!-- Butoni për kthim në dashboard bazuar në rolin e përdoruesit -->
    <a href="<?php echo $dashboard_link; ?>" class="back-to-dashboard-btn">Kthehu në Dashboard</a>
</div>

</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .change-password-container {
        max-width: 450px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        color: #333;
        font-size: 28px;
        margin-bottom: 20px;
    }

    .submit-btn {
        background-color: #4CAF50;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #45a049;
    }

    .back-to-dashboard-btn {
        display: inline-block;
        margin-top: 20px;
        background-color: #3498db;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
    }

    .back-to-dashboard-btn:hover {
        background-color: #2980b9;
    }

    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .error-msg, .success-msg {
        font-weight: bold;
        margin-top: 20px;
    }

    .error-msg {
        color: red;
    }

    .success-msg {
        color: green;
    }
</style>
