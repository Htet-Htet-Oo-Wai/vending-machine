<?php
return "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(18,2) NOT NULL,
    quantity_available INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO products (name, price, quantity_available) 
VALUES ('Coke', '3.99', 50);
INSERT INTO products (name, price, quantity_available) 
VALUES ('Pepsi', '6.885', 50);
INSERT INTO products (name, price, quantity_available) 
VALUES ('Water', '0.5', 50);
";
