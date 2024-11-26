<?php

$host = 'localhost'; // ou l'adresse du serveur

$dbname = 'library_db'; // nom de la base de données

$username = 'root'; // nom d'utilisateur

$password = ''; // mot de passe



try {

    // Création de la connexion à la base de données

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo 'La connexion a échoué : ' . $e->getMessage();

}

?>
<?php

include 'db_connection.php'; // Inclusion de la connexion à la base de données



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];

    $author = $_POST['author'];

    $category = $_POST['category'];



    // Ajouter le livre à la base de données

    $query = "INSERT INTO books (title, author, category) VALUES (:title, :author, :category)";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':title', $title);

    $stmt->bindParam(':author', $author);

    $stmt->bindParam(':category', $category);

    

    if ($stmt->execute()) {

        echo "Le livre a été ajouté avec succès!";

    } else {

        echo "Une erreur s'est produite lors de l'ajout du livre.";

    }

}

?>



<form action="add_book.php" method="POST">

    <label for="title">Titre du livre :</label><br>

    <input type="text" name="title" required><br><br>



    <label for="author">Auteur :</label><br>

    <input type="text" name="author" required><br><br>



    <label for="category">Catégorie :</label><br>

    <input type="text" name="category" required><br><br>



    <button type="submit">Ajouter le livre</button>

</form>

<?php

include 'db_connection.php'; // Inclusion de la connexion à la base de données



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];

    $author = $_POST['author'];

    $category = $_POST['category'];



    // Ajouter le livre à la base de données

    $query = "INSERT INTO books (title, author, category) VALUES (:title, :author, :category)";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':title', $title);

    $stmt->bindParam(':author', $author);

    $stmt->bindParam(':category', $category);

    

    if ($stmt->execute()) {

        echo "Le livre a été ajouté avec succès!";

    } else {

        echo "Une erreur s'est produite lors de l'ajout du livre.";

    }

}

?>



<form action="add_book.php" method="POST">

    <label for="title">Titre du livre :</label><br>

    <input type="text" name="title" required><br><br>



    <label for="author">Auteur :</label><br>

    <input type="text" name="author" required><br><br>



    <label for="category">Catégorie :</label><br>

    <input type="text" name="category" required><br><br>



    <button type="submit">Ajouter le livre</button>

</form>
?php

include 'db_connection.php'; // Inclusion de la connexion à la base de données



session_start();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];

    $password = $_POST['password'];



    // Vérification de l'utilisateur

    $query = "SELECT * FROM users WHERE email = :email";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':email', $email);

    $stmt->execute();



    $user = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($user && password_verify($password, $user['password'])) {

        // Si le mot de passe est correct

        $_SESSION['user_id'] = $user['id'];

        $_SESSION['user_email'] = $user['email'];

        header("Location: dashboard.php"); // Rediriger vers le tableau de bord

        exit;

    } else {

        echo "L'email ou le mot de passe est incorrect.";

    }

}

?>



<form action="login.php" method="POST">

    <label for="email">Email :</label><br>

    <input type="email" name="email" required><br><br>



    <label for="password">Mot de passe :</label><br>

    <input type="password" name="password" required><br><br>



    <button type="submit">Se connecter</button>

</form>

<?php

include 'db_connection.php'; // Inclusion de la connexion à la base de données



session_start();



// Vérifier si l'utilisateur est connecté

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php"); // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion

    exit;

}



if (isset($_GET['book_id'])) {

    $book_id = $_GET['book_id'];

    $user_id = $_SESSION['user_id'];



    // Vérifier si le livre est disponible pour emprunt

    $query = "SELECT * FROM books WHERE id = :book_id AND available = 1";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':book_id', $book_id);

    $stmt->execute();



    if ($stmt->rowCount() > 0) {

        // Si le livre est disponible, ajouter le prêt dans la table des prêts

        $query = "INSERT INTO loans (book_id, user_id, loan_date) VALUES (:book_id, :user_id, NOW())";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':book_id', $book_id);

        $stmt->bindParam(':user_id', $user_id);



        if ($stmt->execute()) {

            // Mettre à jour la disponibilité du livre pour le marquer comme non disponible

            $query = "UPDATE books SET available = 0 WHERE id = :book_id";

            $stmt = $pdo->prepare($query);

            $stmt->bindParam(':book_id', $book_id);

            $stmt->execute();



            echo "Le livre a été emprunté avec succès!";

        } else {

            echo "Une erreur s'est produite lors de l'emprunt du livre.";

        }

    } else {

        echo "Le livre n'est pas disponible pour emprunt.";

    }

}

?>


