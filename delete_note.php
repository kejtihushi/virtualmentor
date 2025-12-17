<?php
session_start();
include('db.php'); // Lidhja me databazën

// Kontrollo nëse përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id']; // Merrni ID-në e studentit nga sesioni

// Kontrollo nëse është dërguar një note_id
if (isset($_GET['note_id']) && isset($_GET['course_id'])) {
    $note_id = $_GET['note_id'];
    $course_id = $_GET['course_id'];

    // Fshi shënimin nga tabela "notes"
    $sql_delete = "DELETE FROM notes WHERE id = ? AND student_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $note_id, $student_id); // Përdorni parameteret për mbrojtje kundër SQL injection

    if ($stmt_delete->execute()) {
        // Fshije shënimin dhe kthehu në faqen e shënimeve të kursit
        header("Location: view_notes.php?course_id=" . $course_id);
        exit();
    } else {
        echo "Gabim gjatë fshirjes: " . $stmt_delete->error; // Printoni gabimin nëse ka ndodhur
    }
} else {
    echo "<p class='error-msg'>Shënimi nuk u gjet!</p>";
}
?>
