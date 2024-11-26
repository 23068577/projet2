import java.sql.Connection;

import java.sql.DriverManager;

import java.sql.SQLException;



public class DatabaseConnection {

    private static final String URL = "jdbc:mysql://localhost:3306/library_db";

    private static final String USER = "root";

    private static final String PASSWORD = "";

    

    public static Connection getConnection() throws SQLException {

        try {

            // Charger le driver JDBC

            Class.forName("com.mysql.cj.jdbc.Driver");

            // Retourner la connexion

            return DriverManager.getConnection(URL, USER, PASSWORD);

        } catch (ClassNotFoundException | SQLException e) {

            throw new SQLException("Erreur de connexion à la base de données", e);

        }

    }

}

import java.sql.Connection;

import java.sql.PreparedStatement;

import java.sql.SQLException;



public class AddBook {



    public static void addBook(String title, String author, String category) {

        String query = "INSERT INTO books (title, author, category) VALUES (?, ?, ?)";

        

        try (Connection connection = DatabaseConnection.getConnection();

             PreparedStatement statement = connection.prepareStatement(query)) {

            

            statement.setString(1, title);

            statement.setString(2, author);

            statement.setString(3, category);

            

            int rowsAffected = statement.executeUpdate();

            if (rowsAffected > 0) {

                System.out.println("Le livre a été ajouté avec succès !");

            } else {

                System.out.println("Une erreur s'est produite lors de l'ajout du livre.");

            }

            

        } catch (SQLException e) {

            e.printStackTrace();

        }

    }

}

import java.sql.*;



public class DisplayBooks {



    public static void displayBooks() {

        String query = "SELECT * FROM books";

        

        try (Connection connection = DatabaseConnection.getConnection();

             Statement statement = connection.createStatement();

             ResultSet resultSet = statement.executeQuery(query)) {

            

            System.out.println("Liste des livres :");

            while (resultSet.next()) {

                int id = resultSet.getInt("id");

                String title = resultSet.getString("title");

                String author = resultSet.getString("author");

                String category = resultSet.getString("category");

                

                System.out.println("ID: " + id + ", Titre: " + title + ", Auteur: " + author + ", Catégorie: " + category);

            }

            

        } catch (SQLException e) {

            e.printStackTrace();

        }

    }

}

import java.sql.*;



public class BorrowBook {



    public static void borrowBook(int userId, int bookId) {

        String query = "INSERT INTO loans (book_id, user_id, loan_date, return_date) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY))";

        

        try (Connection connection = DatabaseConnection.getConnection();

             PreparedStatement statement = connection.prepareStatement(query)) {

            

            statement.setInt(1, bookId);

            statement.setInt(2, userId);

            

            int rowsAffected = statement.executeUpdate();

            if (rowsAffected > 0) {

                // Mettre à jour la disponibilité du livre

                String updateQuery = "UPDATE books SET available = FALSE WHERE id = ?";

                try (PreparedStatement updateStatement = connection.prepareStatement(updateQuery)) {

                    updateStatement.setInt(1, bookId);

                    updateStatement.executeUpdate();

                }

                System.out.println("Le livre a été emprunté avec succès !");

            } else {

                System.out.println("Le livre n'a pas pu être emprunté.");

            }

            

        } catch (SQLException e) {

            e.printStackTrace();

        }

    }

}

import java.util.Scanner;



public class Main {



    public static void main(String[] args) {

        Scanner scanner = new Scanner(System.in);



        while (true) {

            System.out.println("\nGestion des livres et emprunts - Bibliothèque");

            System.out.println("1. Afficher les livres");

            System.out.println("2. Ajouter un livre");

            System.out.println("3. Emprunter un livre");

            System.out.println("4. Quitter");

            System.out.print("Choisissez une option : ");

            

            int choice = scanner.nextInt();

            scanner.nextLine();  // Consomme la nouvelle ligne



            switch (choice) {

                case 1:

                    DisplayBooks.displayBooks();

                    break;

                case 2:

                    System.out.print("Entrez le titre du livre : ");

                    String title = scanner.nextLine();

                    System.out.print("Entrez l'auteur du livre : ");

                    String author = scanner.nextLine();

                    System.out.print("Entrez la catégorie du livre : ");

                    String category = scanner.nextLine();

                    AddBook.addBook(title, author, category);

                    break;

                case 3:

                    System.out.print("Entrez votre ID utilisateur : ");

                    int userId = scanner.nextInt();

                    System.out.print("Entrez l'ID du livre à emprunter : ");

                    int bookId = scanner.nextInt();

                    BorrowBook.borrowBook(userId, bookId);

                    break;

                case 4:

                    System.out.println("Au revoir!");

                    return;

                default:

                    System.out.println("Option invalide. Essayez à nouveau.");

            }

        }

    }

}

<dependency>

    <groupId>mysql</groupId>

    <artifactId>mysql-connector-java</artifactId>

    <version>8.0.26</version> <!-- Vérifiez la dernière version disponible -->

</dependency>


