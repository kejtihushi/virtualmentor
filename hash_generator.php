<?php
// Lista e pedagogëve me fjalëkalimet përkatëse
$professors = [
    ['name' => 'John Smith', 'email' => 'johnsmith@uamd.edu.al', 'password' => 'johnsmith123'],
    ['name' => 'Emma Johnson', 'email' => 'emmajohnson@uamd.edu.al', 'password' => 'emmajohnson123'],
    ['name' => 'Michael Brown', 'email' => 'michaelbrown@uamd.edu.al', 'password' => 'michaelbrown123'],
    ['name' => 'Sarah Davis', 'email' => 'sarahdavis@uamd.edu.al', 'password' => 'sarahdavis123'],
    ['name' => 'David Wilson', 'email' => 'davidwilson@uamd.edu.al', 'password' => 'davidwilson123'],
    ['name' => 'Olivia Martinez', 'email' => 'oliviamartinez@uamd.edu.al', 'password' => 'oliviamartinez123'],
    ['name' => 'James Anderson', 'email' => 'jamesanderson@uamd.edu.al', 'password' => 'jamesanderson123'],
    ['name' => 'Sophia Thomas', 'email' => 'sophiathomas@uamd.edu.al', 'password' => 'sophiathomas123'],
    ['name' => 'Daniel Taylor', 'email' => 'danieltaylor@uamd.edu.al', 'password' => 'danieltaylor123'],
    ['name' => 'Isabella Moore', 'email' => 'isabellamoore@uamd.edu.al', 'password' => 'isabellamoore123'],
    ['name' => 'Matthew Jackson', 'email' => 'matthewjackson@uamd.edu.al', 'password' => 'matthewjackson123'],
    ['name' => 'Mia White', 'email' => 'miawhite@uamd.edu.al', 'password' => 'miawhite123'],
    ['name' => 'Ethan Harris', 'email' => 'ethanharris@uamd.edu.al', 'password' => 'ethanharris123'],
    ['name' => 'Ava Clark', 'email' => 'avaclark@uamd.edu.al', 'password' => 'avaclark123'],
    ['name' => 'William Lewis', 'email' => 'williamlewis@uamd.edu.al', 'password' => 'williamlewis123']
];

// Funksioni për të kthyer passwordet në hash dhe për të krijuar insertin
foreach ($professors as $professor) {
    // Krijo hash për fjalëkalimin për secilin pedagog
    $hashedPassword = password_hash($professor['password'], PASSWORD_DEFAULT);

    // Shfaq të dhënat për insertimin në databazë
    echo "INSERT INTO users (name, email, password, role) VALUES ('" . $professor['name'] . "', '" . $professor['email'] . "', '" . $hashedPassword . "', 'professor');\n";
}
?>
