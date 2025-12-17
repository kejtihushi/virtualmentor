<?php
// Lidhja me databazën
$conn = new mysqli("localhost", "root", "", "university_system"); // Ndrysho me të dhënat tuaja të lidhjes

// Kontrollo nëse lidhja ka dështuar
if ($conn->connect_error) {
    die("Lidhja me databazën dështoi: " . $conn->connect_error);
}

// Merr të gjithë përdoruesit nga databaza
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Përditëso hash-in për secilin përdorues
    while ($row = $result->fetch_assoc()) {
        // Krijo hash të ri për fjalëkalimin e përdoruesit
        $newHashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);

        // Përditëso hash-in në databazë për përdoruesin përkatës
        $updateSql = "UPDATE users SET password = '$newHashedPassword' WHERE id = " . $row['id'];

        // Ekzekuto kërkesën e përditësimit
        if ($conn->query($updateSql) === TRUE) {
            echo "Fjalëkalimi për përdoruesin me ID " . $row['id'] . " u përditësua me sukses.<br>";
        } else {
            echo "Gabim gjatë përditësimit të përdoruesit me ID " . $row['id'] . ": " . $conn->error . "<br>";
        }
    }
} else {
    echo "Nuk u gjetën përdorues në databazë.";
}

// Mbylle lidhjen me databazën
$conn->close();
?>
