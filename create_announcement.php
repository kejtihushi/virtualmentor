<?php
session_start();
include('db.php'); // Lidhja me databazën
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'professor') {
    header("Location: login.php"); // Ridrejto në faqen e login-it
    exit(); // Stop kodit më tej
}
$professor_id = $_SESSION['user_id']; // Merrni ID-në e profesorëve nga sesioni
if (!isset($_GET['course_id'])) {
    header("Location: professor_dashboard.php");
    exit();
}
$course_id = $_GET['course_id'];
$sql = "SELECT * FROM courses WHERE id = ? AND professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $course_id, $professor_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: professor_dashboard.php");
    exit(); // Njoftimi i gabuar
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $title = $_POST['title'];
    $text = $_POST['text'];
    $sql = "INSERT INTO course_announcements (course_id, professor_id, title, text) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $course_id, $professor_id, $title, $text);
    if ($stmt->execute()) {
        $message = "Njoftimi është postuar me sukses.";
        $message_type = "success";  // Lloji i mesazhit: sukses
    } else {
        $message = "Ka ndodhur një gabim gjatë postimit të njoftimit.";
        $message_type = "error";  // Lloji i mesazhit: gabim
    }
}
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['announcement_id'])) {
    $announcement_id = $_POST['announcement_id'];
    $sql_check = "SELECT * FROM course_announcements WHERE id = ? AND professor_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $announcement_id, $professor_id);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    if ($check_result->num_rows > 0) {
        $sql_delete = "DELETE FROM course_announcements WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $announcement_id);
        if ($stmt_delete->execute()) {
            $message = "Njoftimi është fshirë me sukses.";
            $message_type = "success";  // Lloji i mesazhit: sukses
        } else {
            $message = "Ka ndodhur një gabim gjatë fshirjes së njoftimit.";
            $message_type = "error";  // Lloji i mesazhit: gabim
        }
    } else {
        $message = "Njoftimi nuk ekziston ose nuk është i lidhur me ju.";
        $message_type = "error";  // Lloji i mesazhit: gabim
    }
}
$sql_announcements = "SELECT * FROM course_announcements WHERE course_id = ? ORDER BY created_at DESC";
$stmt_announcements = $conn->prepare($sql_announcements);
$stmt_announcements->bind_param("i", $course_id);
$stmt_announcements->execute();
$announcements_result = $stmt_announcements->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krijo Njoftim</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 900px; margin: 0 auto; padding: 20px; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { font-size: 28px; margin-bottom: 20px; color: #333; }
        label { font-size: 14px; font-weight: bold; color: #666; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        input[type="submit"], .button-back, .button-delete { background-color: #4CAF50; color: white; padding: 12px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease; margin-right: 10px; margin-top: 10px; }
        input[type="submit"]:hover, .button-back:hover, .button-delete:hover { background-color: #45a049; }
        .button-back { background-color: #3498db; }
        .button-back:hover { background-color: #2980b9; }
        .announcement-item { background-color: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); }
        .announcement-title { font-size: 20px; font-weight: bold; color: #333; }
        .announcement-text { font-size: 16px; color: #555; margin: 10px 0; }
        .announcement-footer { font-size: 14px; color: #aaa; }
        .success-msg, .error-msg { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; font-size: 16px; }
        .success-msg { background-color: #d4edda; color: #155724; }
        .error-msg { background-color: #f8d7da; color: #721c24; }
        .announcement-list { margin-top: 40px; }
        .announcement-item form { display: inline-block; margin-left: 20px; }
        .toggle-announcements { background-color: #f39c12; color: white; padding: 12px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; width: 100%; text-align: center; margin-top: 20px; }
        .toggle-announcements:hover { background-color: #e67e22; }
        .announcement-list { display: none; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Krijo Njoftim për Lëndën</h2>
        <?php if (isset($message) && $message_type == 'success') : ?>
            <div class="success-msg" id="success-msg"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (isset($message) && $message_type == 'error') : ?>
            <div class="error-msg" id="error-msg"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="hidden" name="action" value="create">
            <label for="title">Titulli:</label>
            <input type="text" id="title" name="title" required>
            <label for="text">Teksti:</label>
            <textarea id="text" name="text" rows="5" required></textarea>
            <input type="submit" value="Posto Njoftimin">
        </form>
        <form action="professor_dashboard.php" method="get">
            <button type="submit" class="button-back">Kthehu në Dashboard</button>
        </form>
    </div>
    <button class="toggle-announcements" onclick="toggleAnnouncements()">Shiko Njoftimet e Mëparshme</button>
    <div class="announcement-list" id="announcement-list">
        <?php
        if ($announcements_result->num_rows > 0) {
            while ($row = $announcements_result->fetch_assoc()) {
                echo "<div class='announcement-item'>";
                echo "<p class='announcement-title'>" . $row['title'] . "</p>";
                echo "<p class='announcement-text'>" . $row['text'] . "</p>";
                echo "<p class='announcement-footer'><small>Publikuar më " . $row['created_at'] . "</small></p>";
                echo "<form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='hidden' name='announcement_id' value='" . $row['id'] . "'>
                        <button type='submit' class='button-delete'>Fshi</button>
                    </form>";
                echo "</div>";
            }
        } else {
            echo "<p>Nuk ka njoftime të mëparshme për këtë lëndë.</p>";
        }
        ?>
    </div>
</div>
<script>
    window.onload = function() {
        setTimeout(function() {
            var successMsg = document.getElementById('success-msg');
            var errorMsg = document.getElementById('error-msg');
            if (successMsg) {
                successMsg.style.display = 'none';
            }
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        }, 3000);
    }

    function toggleAnnouncements() {
        var announcementList = document.getElementById('announcement-list');
        if (announcementList.style.display === 'none' || announcementList.style.display === '') {
            announcementList.style.display = 'block';
        } else {
            announcementList.style.display = 'none';
        }
    }
</script>
</body>
</html>
