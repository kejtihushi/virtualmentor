<?php
$password_input = 'johnsmith123'; // Vendos fjalëkalimin që po teston
$password_hashed = '$2y$10$FXbBjvHjaHbgM2RxT03SV.nq9bp5cYI9ZVGvUge.qBRabX0m1QA.y'; // Vendos hash-in nga databaza

if (password_verify($password_input, $password_hashed)) {
    echo "Fjalëkalimi është i saktë!";
} else {
    echo "Fjalëkalimi është i gabuar!";
}
?>