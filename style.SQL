CREATE DATABASE IF NOT EXISTS library_db;

USE library_db;

CREATE TABLE IF NOT EXISTS users (

    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique

    email VARCHAR(255) NOT NULL UNIQUE,  -- Adresse email de l'utilisateur

    password VARCHAR(255) NOT NULL,      -- Mot de passe (hashé)

    name VARCHAR(255) NOT NULL,          -- Nom de l'utilisateur

    role ENUM('user', 'admin') DEFAULT 'user',  -- Rôle (utilisateur ou administrateur)

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date de création du compte

);

CREATE TABLE IF NOT EXISTS books (

    id INT AUTO_INCREMENT PRIMARY KEY,   -- Identifiant unique

    title VARCHAR(255) NOT NULL,         -- Titre du livre

    author_id INT NOT NULL,              -- Référence à l'auteur

    category_id INT NOT NULL,            -- Référence à la catégorie

    available BOOLEAN DEFAULT TRUE,      -- Disponibilité du livre (true = disponible, false = emprunté)

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date d'ajout du livre

    FOREIGN KEY (author_id) REFERENCES authors(id),  -- Clé étrangère vers la table des auteurs

    FOREIGN KEY (category_id) REFERENCES categories(id)  -- Clé étrangère vers la table des catégories

);

CREATE TABLE IF NOT EXISTS authors (

    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique

    name VARCHAR(255) NOT NULL UNIQUE   -- Nom de l'auteur

);

CREATE TABLE IF NOT EXISTS categories (

    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique

    name VARCHAR(255) NOT NULL UNIQUE   -- Nom de la catégorie

);

CREATE TABLE IF NOT EXISTS loans (

    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique

    book_id INT NOT NULL,               -- Référence au livre emprunté

    user_id INT NOT NULL,               -- Référence à l'utilisateur ayant emprunté le livre

    loan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date d'emprunt

    return_date TIMESTAMP,              -- Date de retour prévue

    actual_return_date TIMESTAMP,       -- Date réelle de retour

    FOREIGN KEY (book_id) REFERENCES books(id),     -- Clé étrangère vers la table des livres

    FOREIGN KEY (user_id) REFERENCES users(id)      -- Clé étrangère vers la table des utilisateurs

);


CREATE TABLE IF NOT EXISTS book_reservations (

    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique

    book_id INT NOT NULL,               -- Référence au livre réservé

    user_id INT NOT NULL,               -- Référence à l'utilisateur ayant réservé le livre

    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de réservation

    expiration_date TIMESTAMP,         -- Date d'expiration de la réservation

    FOREIGN KEY (book_id) REFERENCES books(id),    -- Clé étrangère vers la table des livres

    FOREIGN KEY (user_id) REFERENCES users(id)     -- Clé étrangère vers la table des utilisateurs

);

CREATE INDEX idx_books_title ON books (title);

CREATE INDEX idx_users_email ON users (email);


INSERT INTO users (email, password, name, role) 

VALUES ('user@example.com', 'hashed_password', 'Jean Dupont', 'user');


INSERT INTO authors (name) 

VALUES ('J.K. Rowling');

INSERT INTO categories (name) 

VALUES ('Fantasy');

INSERT INTO books (title, author_id, category_id, available) 

VALUES ('Harry Potter à l\'école des sorciers', 1, 1, TRUE);

INSERT INTO loans (book_id, user_id, loan_date, return_date) 

VALUES (1, 1, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY));




