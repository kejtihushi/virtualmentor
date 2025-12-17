<?php
// Lista e studentëve me fjalëkalimet përkatëse
$students = [
    ['name' => 'Liam Carter', 'email' => 'liamcarter@students.uamd.edu.al', 'password' => 'liamcarter123'],
    ['name' => 'Emma Walker', 'email' => 'emmawalker@students.uamd.edu.al', 'password' => 'emmawalker123'],
    ['name' => 'Noah Robinson', 'email' => 'noahrobinson@students.uamd.edu.al', 'password' => 'noahrobinson123'],
    ['name' => 'Olivia Scott', 'email' => 'oliviascott@students.uamd.edu.al', 'password' => 'oliviascott123'],
    ['name' => 'Lucas Adams', 'email' => 'lucasadams@students.uamd.edu.al', 'password' => 'lucasadams123'],
    ['name' => 'Sophia Baker', 'email' => 'sophiabaker@students.uamd.edu.al', 'password' => 'sophiabaker123'],
    ['name' => 'Mason Green', 'email' => 'masongreen@students.uamd.edu.al', 'password' => 'masongreen123'],
    ['name' => 'Ava Hall', 'email' => 'avahall@students.uamd.edu.al', 'password' => 'avahall123'],
    ['name' => 'Elijah Wright', 'email' => 'elijahwright@students.uamd.edu.al', 'password' => 'elijahwright123']
];

// Funksioni për të kthyer passwordet në hash
foreach ($students as $student) {
    // Krijo hash për fjalëkalimin e secilit student
    $hashedPassword = password_hash($student['password'], PASSWORD_DEFAULT);

    // Shfaq të dhënat për insertimin në databazë
    echo "INSERT INTO users (name, email, password, role) VALUES ('" . $student['name'] . "', '" . $student['email'] . "', '" . $hashedPassword . "', 'student');\n";
}
?>
 