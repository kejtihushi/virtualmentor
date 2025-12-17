<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Ruajtja e një shënimi të ri
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'], $_POST['note_text'])) {
    $course_id = $_POST['course_id'];
    $note_text = $_POST['note_text'];

    $query = "INSERT INTO notes (student_id, course_id, note_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $student_id, $course_id, $note_text);
    if ($stmt->execute()) {
        echo "Shënimi u ruajt!";
    } else {
        echo "Gabim gjatë ruajtjes së shënimit.";
    }
}

// Shfaqja e shënimeve ekzistuese
$query = "
    SELECT n.id, c.course_name, n.note_text, n.created_at
    FROM notes n
    JOIN courses c ON n.course_id = c.id
    WHERE n.student_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Shënimet tuaja:</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p><b>Lënda:</b> " . $row['course_name'] . "<br>";
    echo "<b>Shënimi:</b> " . $row['note_text'] . "<br>";
    echo "<b>Data:</b> " . $row['created_at'] . "</p><hr>";
}
?>

<form method="post">
    <select name="course_id">
        <?php
        // Listojmë lëndët e studentit
        $query = "SELECT c.id, c.course_name FROM courses c JOIN student_courses sc ON c.id = sc.course_id WHERE sc.student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $courses = $stmt->get_result();
        while ($course = $courses->fetch_assoc()) {
            echo "<option value='" . $course['id'] . "'>" . $course['course_name'] . "</option>";
        }
        ?>
    </select>
    <textarea name="note_text" placeholder="Shkruaj shënimin këtu..." required></textarea>
    <button type="submit">Ruaj Shënimin</button>
</form>
