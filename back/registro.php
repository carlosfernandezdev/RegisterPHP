<?php
    require_once("db.php");
    $_Username = trim($_POST['username']);
    $_Password = trim($_POST['password']);
    $_Email = trim($_POST['email']);

    if (empty($_Username) || empty($_Password) || empty($_Email)) {
        die("Todos los campos son obligatorios.");
    } 

    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $_Username, $_Email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("El nombre de usuario o el correo electrónico ya están en uso.");
    }   
    $stmt->close();

    $pas_hash = password_hash($_Password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_Username, $pas_hash, $_Email);
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
    $stmt->close();
    $db->close();
?>