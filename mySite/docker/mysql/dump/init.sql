CREATE DATABASE IF NOT EXISTS scheduleDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scheduleDB;

CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL, 
    password VARCHAR(255) NOT NULL,
    role INT(2) NOT NULL   --      1 - admin, 0 - common user
);

CREATE TABLE IF NOT EXISTS schedule (
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL, 
    description VARCHAR(255) NOT NULL,
    owner VARCHAR(255) NOT NULL,
    deadline DATETIME NOT NULL
);

INSERT INTO users (login, role, password) VALUES
('LilShkrek', 1, 'password'),
('Blob', 0, 'parol');

INSERT INTO schedule (name, description, owner, deadline) VALUES
('Eat', 'Eat smth', 'Blob', '2022-06-16 16:37:23'),
('Drink', 'Drink smth', 'Blob', '2022-06-16 16:47:27');
