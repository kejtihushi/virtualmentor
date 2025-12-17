<?php
session_start(); // Përdoret për të ruajtur informacionin në sesionin e përdoruesit

// Krijoni lidhjen me bazën e të dhënave
$servername = "localhost"; // Ose emri i serverit tuaj MySQL
$username = "root"; // Emri i përdoruesit për MySQL
$password = ""; // Fjalkalimi për MySQL
$dbname = "virtualmentor"; // Emri i databazës që përdorni

// Krijoni lidhjen
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrolloni lidhjen
if ($conn->connect_error) {
    die("Lidhja dështoi: " . $conn->connect_error);
}

// Kontrollo nëse është dërguar formulari i logimit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merrni të dhënat nga formulari
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sigurohuni që të dyja fushat të jenë të mbushura
    if (!empty($email) && !empty($password)) {
        // Kërkoni përdoruesin në bazën e të dhënave
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Nëse gjejmë përdoruesin
        if ($result->num_rows > 0) {
            // Merrni të dhënat e përdoruesit
            $row = $result->fetch_assoc();

            // Kontrolloni nëse fjalëkalimi i dhënë përputhet me të koduarin në bazë
            if (password_verify($password, $row['password'])) {
                // Regjistrohu si i loguar
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_role'] = $row['role'];

                // Regjenero ID e sesionit për siguri
                session_regenerate_id(true);

                // Debugging output
                echo "<pre>";
                var_dump($_SESSION);
                echo "</pre>";

                // Redirektoni në dashboardin përkatës
                if ($_SESSION['user_role'] == 'student') {
                    header("Location: student_dashboard.php");
                    exit();
                } else if ($_SESSION['user_role'] == 'professor') {
                    header("Location: professor_dashboard.php");
                    exit();
                } else {
                    // Nëse nuk është as student as profesor
                    $error = "Roli i përdoruesit është i panjohur!";
                }
            } else {
                // Fjalëkalimi i gabuar
                $error = "Fjalëkalimi është i gabuar!";
            }
        } else {
            // Nuk u gjet përdoruesi
            $error = "Emaili nuk është i regjistruar!";
        }
    } else {
        // Fushat janë të zbrazëta
        $error = "Ju lutem, plotësoni të gjitha fushat!";
    }
}

// Mbyllni lidhjen me databazën
$conn->close();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: linear-gradient(45deg, #a8c9e6, #c3daf9, #f3c7e1, #d9f9c7);
        background-size: 400% 400%;
        animation: gradientAnimation 15s ease infinite;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 300px;
        border: 2px solid #3498db;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
    }

    label {
        font-size: 14px;
        color: #555;
    }

    input[type="email"], input[type="password"], input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
    }

    input[type="email"]:hover, input[type="password"]:hover, input[type="submit"]:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border: 1px solid #2980b9;
    }

    input[type="submit"] {
        background-color: #3498db;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #2980b9;
    }

    .error-message {
        color: red;
        font-weight: bold;
        margin-top: 10px;
        text-align: center;
    }

    @keyframes fadeOut {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

    .fade-out {
        animation: fadeOut 2s forwards;
    }

    @keyframes gradientAnimation {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
</head>
<body>
    <div class="container">
        <h2>STUVIMENT</h2>
        
        <?php
        // Shfaqni gabimet nëse ka
        if (isset($error)) {
            echo "<p class='error-message' id='errorMessage' class='fade-out'>$error</p>";
        }
        ?>

        <form action="login.php" method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Fjalëkalimi:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Hyni">
        </form>
    </div>

    <script>
        window.onload = function() {
            var errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 2000);
            }
        }
    </script>
</body>
</html>
