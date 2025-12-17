<?php
session_start();
include('db.php');

// Sigurohuni që përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Sigurohuni që ka një course_id në URL
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Query për të marrë njoftimet për këtë lëndë, emrin e lëndës dhe emrin e pedagogut (nga tabela 'users')
    $sql_announcements = "
        SELECT ca.*, c.course_name, u.name AS professor_name
        FROM course_announcements ca
        JOIN courses c ON ca.course_id = c.id
        JOIN users u ON c.professor_id = u.id  -- përdorim tabelën 'users' për emrin e pedagogut
        WHERE ca.course_id = ?
        ORDER BY ca.created_at DESC
    ";

    $stmt_announcements = $conn->prepare($sql_announcements);
    $stmt_announcements->bind_param("i", $course_id);
    $stmt_announcements->execute();
    $announcements_result = $stmt_announcements->get_result();

    // Kontrollo njoftimet dhe shfaq
    if ($announcements_result->num_rows > 0) {
        // Shfaq emrin e lëndës dhe emrin e pedagogut
        $announcement = $announcements_result->fetch_assoc(); // Merr një rresht nga njoftimet për të marrë lëndën dhe pedagogun
        echo "<h3 class='course-header'>Njoftimet për Lëndën: " . $announcement['course_name'] . " - Profesor: " . $announcement['professor_name'] . "</h3>";

        // Përsëri vendosni një loop për të shfaqur njoftimet
        do {
            echo "<div class='announcement-item'>";
            echo "<h4 class='announcement-title'>" . $announcement['title'] . "</h4>";
            echo "<p class='announcement-text'>" . $announcement['text'] . "</p>";
            echo "<small class='announcement-date'>Publikuar më: " . $announcement['created_at'] . "</small>";
            echo "</div>";
        } while ($announcement = $announcements_result->fetch_assoc());  // Përsërit derisa të shfaqen të gjitha njoftimet
    } else {
        echo "<p class='no-announcements'>Nuk ka njoftime për këtë lëndë.</p>";
    }
} else {
    echo "<p class='error-message'>Invalid course.</p>";
}
?>

<!-- Butoni për të u kthyer në dashboard -->
<div style="text-align: center; margin-top: 30px;">
    <a href="student_dashboard.php">
        <button class="go-back-btn">Kthehu në Dashboard</button>
    </a>
</div>

<style>
/* Përgjithshme */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #eef2f7;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Header i kursit */
.course-header {
    font-size: 26px;
    color: #2c3e50;
    margin-top: 40px;
    text-align: center;
    font-weight: 600;
    padding-bottom: 10px;
    /* Heqim vijën blu */
}

/* Titulli i njoftimit */
.announcement-title {
    font-size: 20px;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 500;
}

/* Teksti i njoftimit */
.announcement-text {
    font-size: 16px;
    color: #34495e;
    line-height: 1.6;
    margin-bottom: 15px;
}

/* Data e njoftimit */
.announcement-date {
    font-size: 14px;
    color: #7f8c8d;
    display: block;
    margin-top: 10px;
}

/* Kutia e njoftimit */
.announcement-item {
    background-color: #fff;
    padding: 20px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    max-width: 800px;
}

.announcement-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Mesazhi kur nuk ka njoftime */
.no-announcements {
    text-align: center;
    font-size: 18px;
    color: #e74c3c;
    font-weight: bold;
}

/* Mesazhi për gabim */
.error-message {
    color: #e74c3c;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}

/* Shtimi i një ndjesie të lehtë animimi për ngarkimin e elementëve */
.announcement-item {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Butoni "Kthehu në Dashboard" */
.go-back-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

.go-back-btn:hover {
    background-color: #2980b9;
}
</style>
