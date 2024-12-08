<?php
$password = '$2y$10$DnPOPPEeey1CFSKKGVtbDOP1UDqf7dFsjL8wa4gLVsDPehgP64/h2';
return "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO users (name, role_id, email, password) 
VALUES ('Admin User', 1, 'admin@example.com', '$password');
INSERT INTO users (name, role_id, email, password) 
VALUES ('Customer', 2, 'customer@example.com', '$password');
";
