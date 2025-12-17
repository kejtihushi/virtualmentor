<?php
session_start();
include('db.php');

// Kontrollo nëse përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id']; // Merrni ID-në e studentit nga sesioni

// Kontrolloni nëse është dhënë një note_id
if (isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];

    // Merrni shënimin nga databaza
    $sql = "SELECT note_text, course_id FROM notes WHERE id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $note = $result->fetch_assoc();
        $note_text = $note['note_text']; // Përshtat variablën note_text me të dhënat nga DB
        $course_id = $note['course_id']; // Merrni course_id për të kthyer në view_notes.php
    } else {
        echo "Shënimi nuk u gjet.";
        exit();
    }

    // Përdorimi i metodës POST për të ruajtur ndryshimet
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updated_note = $_POST['note_text'];

        // Përshtat shënimin në databazë
        $sql = "UPDATE notes SET note_text = ? WHERE id = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $updated_note, $note_id, $student_id);
        if ($stmt->execute()) {
            $success_message = "Shënimi u përditësua me sukses.";
        } else {
            $error_message = "Gabim gjatë përditësimit.";
        }
    }
} else {
    echo "Id e shënimit nuk është specifikuar.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edito Shënimin</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Funksioni për të fshehur mesazhin pas 3 sekondash
        function hideMessage() {
            setTimeout(function() {
                document.getElementById('success-msg').style.display = 'none';
            }, 3000); // 3000 milisekonda = 3 sekonda
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="edit-note-container">
            <?php
            if (isset($success_message)) {
                echo "<p id='success-msg' class='success-msg'>$success_message</p>";
                echo "<script>hideMessage();</script>"; // Aktivizoni funksionin për të fshirë mesazhin pas 3 sekondash
            }
            if (isset($error_message)) {
                echo "<p class='error-msg'>$error_message</p>";
            }
            ?>

            <h2>Edito Shënimin</h2>
            <form method="post">
                <textarea name="note_text" required><?php echo htmlspecialchars($note_text); ?></textarea><br>
                <input type="submit" value="Ruaj ndryshimet" class="submit-btn">
            </form>

            <!-- Butoni për kthim në shënimet -->
            <a href="view_notes.php?course_id=<?php echo $course_id; ?>" class="back-btn">Kthehu në shënimet</a>
        </div>
    </div>
</body>
</html>

<style>
/* Përgjithësisht kontenieri dhe hapsira */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
}

/* Stilizimi i kontenierit të faqes */
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}

/* Editimi i shënimit */
.edit-note-container {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    padding: 20px;
    text-align: center;
}

/* Titulli */
.edit-note-container h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Teksti i shënimit */
textarea {
    width: 100%;
    height: 200px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: 'Arial', sans-serif;
    margin-bottom: 20px;
    resize: none;
    transition: border-color 0.3s ease;
}

textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Butoni i ruajtjes */
.submit-btn {
    background-color: #28a745;
    color: #fff;
    padding: 8px 15px; /* Shkurtohet padding-u */
    border: none;
    border-radius: 5px; /* Kthesë e butë për këndet */
    cursor: pointer;
    font-size: 14px; /* Shkurtohet fonti */
    width: auto; /* Dega e butonit do të shkojë vetëm sa madhësia e përmbajtjes */
    text-align: center;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.submit-btn:hover {
    background-color: #218838;
}

/* Butoni i kthimit */
.back-btn {
    display: inline-block;
    margin-top: 10px; /* Reduktimi i distancës */
    text-decoration: none;
    background-color: #007bff;
    color: #fff;
    padding: 8px 15px; /* Shkurtohet padding-u */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px; /* Shkurtohet fonti */
    width: auto; /* Dega e butonit do të shkojë vetëm sa madhësia e përmbajtjes */
    text-align: center;
    transition: background-color 0.3s ease;
}

.back-btn:hover {
    background-color: #0056b3;
}

/* Mesazhi i suksesit */
.success-msg {
    color: green;
    font-weight: bold;
    margin-bottom: 20px;
    padding: 10px;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    display: inline-block;
    width: 100%;
    animation: fadeIn 1s ease-in-out;
}

/* Mesazhi i gabimit */
.error-msg {
    color: red;
    font-weight: bold;
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    display: inline-block;
    width: 100%;
}

/* Animimi për mesazhin e suksesit */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
