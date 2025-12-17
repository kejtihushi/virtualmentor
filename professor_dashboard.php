<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'professor') {
    header("Location: login.php");
    exit();
}

$professor_id = $_SESSION['user_id'];

$sql = "
    SELECT c.course_name, c.year, u.name AS student_name, c.id AS course_id, u.id AS student_id
    FROM courses c
    JOIN student_courses sc ON c.id = sc.course_id
    JOIN users u ON sc.student_id = u.id
    WHERE c.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profesor Dashboard</title>
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
            position: relative;
        }

        .header-dashboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin: 0;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        h3 {
            color: #333;
        }

        .course-details {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .no-courses {
            color: #f44336;
            font-weight: bold;
        }

        .toggle-student-list {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .toggle-student-list:hover {
            background-color: #45a049;
        }

        .announcement-btn {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .announcement-btn:hover {
            background-color: #2980b9;
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

        /* Stilizimi i listës së studentëve */
        .students-list {
            list-style-type: none;
            margin-top: 10px;
            padding-left: 20px;
        }

        .students-list li {
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f1f1f1;
        }

        .students-list li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header-dashboard">
        <h2>Mirësevini, <?php echo $_SESSION['user_name']; ?></h2>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Dil</button>
        </form>
    </div>

    <h3>Lëndët që jepni:</h3>
    <?php
    if ($result->num_rows > 0) {
        $current_course = "";
        while ($row = $result->fetch_assoc()) {
            if ($current_course != $row['course_name']) {
                if ($current_course != "") {
                    echo "</ul>";
                }
                echo "<div class='course-details'>";
                echo "<p><strong>Lënda:</strong> " . $row['course_name'] . " <strong>Viti:</strong> " . $row['year'] . "</p>";
                echo "<p><a href='create_announcement.php?course_id=" . $row['course_id'] . "' class='announcement-btn'>Shkruaj Njoftime</a></p>";
                echo "<button class='toggle-student-list' data-course-id='" . $row['course_id'] . "'>Shiko Studentët</button>";
                echo "<ul id='students-list-" . $row['course_id'] . "' class='students-list' style='display:none;'>";
            }
            echo "<li>" . $row['student_name'] . "</li>"; // Tani vetëm emri i studentit
            $current_course = $row['course_name'];
        }
        echo "</ul></div>";
    } else {
        echo "<p class='no-courses'>Nuk keni asnjë student të regjistruar për lëndët që jepni.</p>";
    }
    ?>

    <!-- Butoni për Ndryshim Fjalëkalimi -->
    <a href='change_password.php' class='change-password-btn'>Ndrysho Fjalëkalimin</a>
</div>

<script>
    document.querySelectorAll('.toggle-student-list').forEach(button => {
        button.addEventListener('click', function () {
            const courseId = this.getAttribute('data-course-id');
            const studentList = document.getElementById('students-list-' + courseId);

            if (studentList.style.display === 'none' || studentList.style.display === '') {
                studentList.style.display = 'block';
            } else {
                studentList.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
