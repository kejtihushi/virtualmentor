<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Shto kodin për të numëruar njoftimet e reja në forum
$sql = "
    SELECT COUNT(*) AS unread_count
    FROM forum_posts fp
    WHERE NOT EXISTS (
        SELECT 1 FROM forum_views fv
        WHERE fv.post_id = fp.id AND fv.student_id = ?
    )
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_unread = $stmt->get_result();
$row_unread = $result_unread->fetch_assoc();
$unread_count = $row_unread['unread_count'];

if (isset($_POST['save_note'])) {
    $note_text = $_POST['note_text'];
    $course_id = $_POST['course_id'];

    if (!empty($note_text) && !empty($course_id)) {
        $sql = "INSERT INTO notes (student_id, course_id, note_text, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $student_id, $course_id, $note_text);

        if ($stmt->execute()) {
            $success_msg = "Shënimi u ruajt me sukses!";
            $show_success_msg_for_course = $course_id;
        } else {
            $error_msg = "Ka ndodhur një gabim gjatë ruajtjes së shënimit.";
        }
    } else {
        $error_msg = "Ju lutem, plotësoni të gjitha fushat.";
    }
}

$sql = "
    SELECT c.id AS course_id, c.course_name, c.year, p.name AS professor_name 
    FROM student_courses sc
    JOIN courses c ON sc.course_id = c.id
    JOIN users p ON c.professor_id = p.id
    WHERE sc.student_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<div class='dashboard-container'>";
echo "<div class='header-dashboard'>";
echo "<h2>Mirësevini, " . $_SESSION['user_name'] . "</h2>";
echo "<div class='top-buttons'>";

// Butoni i forumit me pikë të kuqe nëse ka njoftime të reja
echo "<a href='forum.php' class='forum-btn'>Forum";
if ($unread_count > 0) {
    echo " <span class='notification-dot'></span>";
}
echo "</a>";

echo "<form action='logout.php' method='post'>
        <button type='submit' class='logout-btn'>Dil</button>
      </form>";
echo "</div>";
echo "</div>";

echo "<h3>Lëndët tuaja:</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='course-details'>";
        echo "<p><strong>Lënda:</strong> " . $row['course_name'] . "</p>";
        echo "<p><strong>Viti:</strong> " . $row['year'] . "</p>";
        echo "<p><strong>Profesori:</strong> " . $row['professor_name'] . "</p>";

        echo "<div class='course-actions'>";
        echo "<button onclick='toggleNoteForm(" . $row['course_id'] . ")'>Shkruaj</button>";

        echo "<form method='GET' action='view_notes.php' style='margin: 0;'>
                <input type='hidden' name='course_id' value='" . $row['course_id'] . "'>
                <button type='submit'>Shënimet</button>
              </form>";

        echo "<form method='GET' action='view_announcements.php' style='margin: 0;'>
                <input type='hidden' name='course_id' value='" . $row['course_id'] . "'>
                <button type='submit'>Njoftimet</button>
              </form>";
        echo "</div>";

        echo "<div id='note-form-" . $row['course_id'] . "' class='note-form' style='display: none;'>
                <form method='POST' action=''>
                    <textarea name='note_text' placeholder='Shkruaj shënimin tuaj...' required></textarea><br>
                    <input type='hidden' name='course_id' value='" . $row['course_id'] . "'>
                    <input type='submit' name='save_note' value='Ruaj Shënimin' class='submit-btn'>
                </form>
              </div>";

        if (isset($success_msg) && $show_success_msg_for_course == $row['course_id']) {
            echo "<p class='success-msg' id='success-msg-" . $row['course_id'] . "'>$success_msg</p>";
        }
        if (isset($error_msg)) {
            echo "<p class='error-msg'>$error_msg</p>";
        }

        echo "</div>";
    }
} else {
    echo "<p class='no-courses'>Nuk keni lëndë të regjistruara.</p>";
}

echo "<a href='change_password.php' class='change-password-btn'>Ndrysho Fjalëkalimin</a>";
echo "</div>";
?>

<script>
function toggleNoteForm(courseId) {
    var form = document.getElementById('note-form-' + courseId);
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

<?php if (isset($show_success_msg_for_course)): ?>
    setTimeout(function() {
        var successMsg = document.getElementById('success-msg-<?php echo $show_success_msg_for_course; ?>');
        if (successMsg) {
            successMsg.style.display = 'none';
        }
    }, 3000);
<?php endif; ?>
</script>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.dashboard-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.header-dashboard {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.top-buttons {
    display: flex;
    gap: 10px;
}

.logout-btn,
.forum-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    line-height: 1;
    height: 40px;
    box-sizing: border-box;
}

.logout-btn {
    background-color: #e74c3c;
}

.logout-btn:hover {
    background-color: #c0392b;
}

.forum-btn:hover {
    background-color: #2e86de;
}

h2 {
    color: #333;
    font-size: 24px;
    margin: 0;
}

h3 {
    color: #333;
}

.course-details {
    background-color: #f9f9f9;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 6px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.course-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.course-actions button {
    padding: 8px 12px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    width: 120px;
    text-align: center;
}

.course-actions button:hover {
    background-color: #2980b9;
}

.note-form {
    margin-top: 10px;
    padding: 15px;
    background-color: #f1f1f1;
    border-radius: 6px;
}

textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    height: 100px;
}

.submit-btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.success-msg {
    color: green;
    font-weight: bold;
    margin-top: 20px;
}

.error-msg {
    color: red;
    font-weight: bold;
    margin-top: 20px;
}

.no-courses {
    color: #f44336;
    font-weight: bold;
}

.change-password-btn {
    display: block;
    margin-top: 20px;
    background-color: #e67e22;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    text-decoration: none;
    width: fit-content;
    margin-left: auto;
    margin-right: auto;
}

.change-password-btn:hover {
    background-color: #d35400;
}

/* Stili për pikën e kuqe */
.notification-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
    margin-left: 5px;
    vertical-align: middle;
}
</style>
