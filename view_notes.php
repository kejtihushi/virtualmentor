<?php
session_start();
include('db.php'); // Lidhja me databazën

// Kontrollo nëse përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id']; // Merrni ID-në e studentit nga sesioni

// Kontrollo nëse është dërguar një course_id
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Kërkoni shënimet për këtë lëndë
    $sql = "
        SELECT n.id AS note_id, n.note_text, n.created_at, c.course_name 
        FROM notes n
        JOIN courses c ON n.course_id = c.id
        WHERE n.student_id = ? AND n.course_id = ?
        ORDER BY n.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Shfaqni shënimet
    echo "<div class='notes-container'>";
    if ($row = $result->fetch_assoc()) {
        echo "<h2>Shënimet për lëndën: " . htmlspecialchars($row['course_name']) . "</h2>";
        echo "<a href='student_dashboard.php' class='back-btn'>Kthehu në dashboard</a>"; // Butoni për kthim në dashboard

        // Përsëri kërko të gjitha shënimet, pasi jemi te kategoria e kursit
        $result->data_seek(0); // Rikoloni në rreshtat fillestarë të të dhënave
        while ($row = $result->fetch_assoc()) {
            echo "<div class='note-details'>";
            echo "<p><strong>Shënimi:</strong> " . nl2br(htmlspecialchars($row['note_text'])) . "</p>";
            echo "<p><strong>Data:</strong> " . $row['created_at'] . "</p>";
            
            // Butoni për Editim
            echo "<a href='edit_note.php?note_id=" . $row['note_id'] . "' class='edit-btn'>Edito</a>";

            // Butoni për Fshirje
            echo "<a href='delete_note.php?note_id=" . $row['note_id'] . "&course_id=" . $course_id . "' class='delete-btn' onclick='return confirm(\"Jeni të sigurt që doni të fshini këtë shënim?\");'>Fshi</a>";

            echo "</div>";
        }
    } else {
        echo "<p class='no-notes'>Nuk keni shënime për këtë lëndë.</p>";
        echo "<a href='student_dashboard.php' class='back-btn'>Kthehu në dashboard</a>"; // Butoni për kthim në dashboard kur nuk ka shënime
    }
    echo "</div>";
} else {
    echo "<p class='error-msg'>Lënda nuk është specifikuar.</p>";
}
?>

<!-- Shtimi i stilizimeve CSS në të njëjtin dokument PHP -->
<style>
/* Stilizimi për shënimet dhe butonat */
.notes-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

.note-details {
    background-color: #f9f9f9;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 6px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.note-details p {
    margin: 10px 0;
    font-size: 16px;
    color: #333;
}

/* Butoni për kthim në dashboard */
.back-btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.2s;
}

.back-btn:hover {
    background-color: #45a049;
    transform: scale(0.95);
}

/* Butonat Edito */
.edit-btn {
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    display: inline-block;
    background-color: #3498db;
    color: white;
    margin-right: 10px;
}

.edit-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Butonat Fshi */
.delete-btn {
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    display: inline-block;
    background-color: #e74c3c;
    color: white;
}

.delete-btn:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Mesazhe gabimi ose pa shënime */
.no-notes {
    color: #f44336;
    font-weight: bold;
    font-size: 18px;
    text-align: center;
}

.error-msg {
    color: #f44336;
    font-weight: bold;
    font-size: 18px;
    text-align: center;
}
</style>
