<?php
session_start();
include('db.php'); // Lidhja me databazën

// Kontrollo nëse përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id']; // Merrni ID-në e studentit nga sesioni

// Kontrollo nëse është dërguar formulari për postimin e njoftimit
if (isset($_POST['post_message'])) {
    $post_text = $_POST['post_text'];

    // Kontrollo nëse ka tekst për postim
    if (!empty($post_text)) {
        $sql = "INSERT INTO forum_posts (student_id, post_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $student_id, $post_text);

        if ($stmt->execute()) {
            $success_msg = "Njoftimi u postua me sukses!";
        } else {
            $error_msg = "Ka ndodhur një gabim gjatë postimit të njoftimit.";
        }
    } else {
        $error_msg = "Ju lutem, shkruani një njoftim për të postuar.";
    }
}

// Kontrollo nëse është dërguar formulari për postimin e përgjigjes
if (isset($_POST['post_reply'])) {
    $reply_text = $_POST['reply_text'];
    $post_id = $_POST['post_id'];

    if (!empty($reply_text) && !empty($post_id)) {
        $sql = "INSERT INTO forum_replies (post_id, student_id, reply_text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $post_id, $student_id, $reply_text);

        if ($stmt->execute()) {
            $success_msg = "Përgjigjja u postua me sukses!";
        } else {
            $error_msg = "Gabim gjatë postimit të përgjigjes.";
        }
    } else {
        $error_msg = "Ju lutem, shkruani një përgjigje për të postuar.";
    }
}

// Merrni postimet nga databaza
$sql = "SELECT fp.id AS post_id, fp.post_text, fp.created_at, u.name AS student_name 
        FROM forum_posts fp 
        JOIN users u ON fp.student_id = u.id 
        ORDER BY fp.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Njoftimet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 36px;
        }

        .forum-post {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .forum-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .forum-post h3 {
            font-size: 24px;
            color: #34495e;
        }

        .forum-post p {
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .reply-btn {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reply-btn:hover {
            background-color: #2980b9;
        }

        textarea {
            width: 100%;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            font-size: 16px;
            color: #333;
            resize: vertical;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
            margin-bottom: 30px;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .back-btn {
            display: inline-block;
            background-color: #8e44ad;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #732d91;
        }

        .success-msg, .error-msg {
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success-msg {
            background-color: #2ecc71;
            color: white;
        }

        .error-msg {
            background-color: #e74c3c;
            color: white;
        }

        .reply-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f4f6f7;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            .forum-post {
                padding: 20px;
            }

            h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Forum Njoftimesh</h2>

        <?php
        if (isset($success_msg)) {
            echo "<p class='success-msg' id='success-msg'>$success_msg</p>";
        }
        if (isset($error_msg)) {
            echo "<p class='error-msg' id='error-msg'>$error_msg</p>";
        }
        ?>

        <!-- Formulari për postimin e njoftimit -->
        <form method="POST" action="">
            <textarea name="post_text" placeholder="Postoni një njoftim..." required></textarea><br>
            <input type="submit" name="post_message" value="Posto Njoftimin" class="submit-btn">
        </form>

        <h3>Njoftimet e Forumit:</h3>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='forum-post'>";
                echo "<h3>Postuar nga: " . $row['student_name'] . "</h3>";
                echo "<p><strong>Koha:</strong> " . $row['created_at'] . "</p>";
                echo "<p><strong>Njoftimi:</strong> " . $row['post_text'] . "</p>";

                // Butoni për të hapur formularin për përgjigje
                echo "<button class='reply-btn' onclick='toggleReplyForm(" . $row['post_id'] . ")'>Përgjigju</button>";

                // Formulari për përgjigje
                echo "<div id='reply-form-" . $row['post_id'] . "' class='reply-form' style='display: none;'>
                        <form method='POST' action=''>
                            <textarea name='reply_text' placeholder='Shkruani përgjigjen tuaj...' required></textarea><br>
                            <input type='hidden' name='post_id' value='" . $row['post_id'] . "'>
                            <input type='submit' name='post_reply' value='Posto Përgjigjen' class='submit-btn'>
                        </form>
                    </div>";

                // Shfaq përgjigjet e postimeve
                $replies_sql = "SELECT fr.reply_text, fr.created_at, u.name AS student_name 
                                FROM forum_replies fr 
                                JOIN users u ON fr.student_id = u.id 
                                WHERE fr.post_id = ? 
                                ORDER BY fr.created_at DESC";
                $replies_stmt = $conn->prepare($replies_sql);
                $replies_stmt->bind_param("i", $row['post_id']);
                $replies_stmt->execute();
                $replies_result = $replies_stmt->get_result();

                if ($replies_result->num_rows > 0) {
                    echo "<h4>Përgjigjet:</h4>";
                    while ($reply = $replies_result->fetch_assoc()) {
                        echo "<p><strong>" . $reply['student_name'] . ":</strong> " . $reply['reply_text'] . " <em>(" . $reply['created_at'] . ")</em></p>";
                    }
                } else {
                    echo "<p>Nuk ka përgjigje për këtë postim.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p class='error-msg'>Nuk ka njoftime të postuara.</p>";
        }
        ?>

        <a href="student_dashboard.php" class="back-btn">Kthehu në Dashboard</a>
    </div>

    <script>
        // Funksioni për të hapur dhe mbyllur formularin për përgjigje
        function toggleReplyForm(postId) {
            var form = document.getElementById('reply-form-' + postId);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        // Funksioni për të fshirë mesazhin pas 3 sekondash
        function hideMessage(id) {
            setTimeout(function() {
                var message = document.getElementById(id);
                if (message) {
                    message.style.display = 'none';
                }
            }, 3000);
        }

        // Thirrja e funksionit për të fshehur mesazhet pas 3 sekondash
        window.onload = function() {
            if (document.getElementById('success-msg')) {
                hideMessage('success-msg');
            }
            if (document.getElementById('error-msg')) {
                hideMessage('error-msg');
            }
        };
    </script>

<?php
// Në fund të faqes forum.php, regjistro postimet si të lexuara për studentin
$sql = "SELECT id FROM forum_posts";
$result_views = $conn->query($sql);

while ($row = $result_views->fetch_assoc()) {
    $post_id = $row['id'];
    // Kontrollo nëse ky postim nuk është regjistruar si i lexuar nga studenti
    $check = $conn->prepare("SELECT id FROM forum_views WHERE student_id = ? AND post_id = ?");
    $check->bind_param("ii", $student_id, $post_id);
    $check->execute();
    $res = $check->get_result();
    
    if ($res->num_rows == 0) {
        // Shto hyrjen si që postimi është lexuar
        $insert = $conn->prepare("INSERT INTO forum_views (student_id, post_id) VALUES (?, ?)");
        $insert->bind_param("ii", $student_id, $post_id);
        $insert->execute();
    }
}
?>
</body>
</html>
