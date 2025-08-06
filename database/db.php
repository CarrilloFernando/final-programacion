<?php

class Database
{
    private $host = "localhost";
    private $db_name = "bd_usuarios";
    private $username = "root";
    private $password = "";
    public $conn;

    public function obtenerConexion()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }

    // Función para validar usuario
    public function validarUsuario($nombre_o_email, $password)
    {
        $query = "SELECT id_usuario, nombre_usuario, password, verificado, id_estado, id_rol FROM usuarios 
                WHERE nombre_usuario = :nombre_o_email OR email = :nombre_o_email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_o_email', $nombre_o_email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario['verificado'] == 0) {
                return ["status" => "error", "message" => "La cuenta no está verificada."];
            }

            if ($usuario['id_estado'] != 1) {
                return ["status" => "error", "message" => "La cuenta está suspendida o eliminada."];
            }

            if (password_verify($password, $usuario['password'])) {
                return [
                    "status" => "success",
                    "message" => "Sesión iniciada correctamente.",
                    "user" => [
                        "id_usuario" => $usuario['id_usuario'],
                        "nombre_usuario" => $usuario['nombre_usuario'],
                        "id_estado" => $usuario['id_estado'],
                        "id_rol" => $usuario['id_rol']
                    ]
                ];
            } else {
                return ["status" => "error", "message" => "Contraseña incorrecta."];
            }
        } else {
            return ["status" => "error", "message" => "El usuario no existe."];
        }
    }

    public function getUsuarios()
    {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarUsuario($nombre_usuario, $apellido, $email, $password)
    {
        // Verificar si el nombre de usuario ya existe
        $queryCheckUsername = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $stmtCheckUsername = $this->conn->prepare($queryCheckUsername);
        $stmtCheckUsername->bindParam(':nombre_usuario', $nombre_usuario);
        $stmtCheckUsername->execute();

        if ($stmtCheckUsername->fetchColumn() > 0) {
            return ["status" => "error", "message" => "El nombre de usuario ya existe."];
        }

        // Verificar si el email ya existe
        $queryCheckEmail = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmtCheckEmail = $this->conn->prepare($queryCheckEmail);
        $stmtCheckEmail->bindParam(':email', $email);
        $stmtCheckEmail->execute();

        if ($stmtCheckEmail->fetchColumn() > 0) {
            return ["status" => "error", "message" => "El email ya está registrado."];
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $token_verificacion = bin2hex(random_bytes(16));

        $queryInsert = "INSERT INTO usuarios (nombre_usuario, apellido, email, password, verificado, token_verificacion, id_estado) 
                        VALUES (:nombre_usuario, :apellido, :email, :password, 0, :token_verificacion, 4)";
        $stmtInsert = $this->conn->prepare($queryInsert);
        $stmtInsert->bindParam(':nombre_usuario', $nombre_usuario);
        $stmtInsert->bindParam(':apellido', $apellido);
        $stmtInsert->bindParam(':email', $email);
        $stmtInsert->bindParam(':password', $passwordHash);
        $stmtInsert->bindParam(':token_verificacion', $token_verificacion);

        if ($stmtInsert->execute()) {
            $this->enviarCorreoVerificacion($email, $token_verificacion);
            return ["status" => "success", "message" => "Usuario registrado correctamente. Verifique su correo para activar la cuenta."];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar el usuario."];
        }
    }

    public function enviarCorreoVerificacion($email, $token_verificacion)
    {
        // Cargar PHPMailer solo si aún no fue cargado
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            require_once '../PHPMailer/src/PHPMailer.php';
            require_once '../PHPMailer/src/SMTP.php';
            require_once '../PHPMailer/src/Exception.php';
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'florrolito@gmail.com';
            $mail->Password = 'soez kekb uxzb umac';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('florrolito@gmail.com', 'foro_virtual');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Verificación de Cuenta';
            $mail->Body = "
                <h1>Verificación de Cuenta</h1>
                <p>Gracias por registrarte. Haz clic en el enlace a continuación para verificar tu cuenta:</p>
                <a href='http://localhost/finalprogramacion/login/verificar.php?token=$token_verificacion'>Verificar Cuenta</a>
                <p>Si no solicitaste este registro, ignora este correo.</p>
            ";

            $mail->send();
            return ["status" => "success", "message" => "Correo enviado correctamente."];
        } catch (Exception $e) {
            return ["status" => "error", "message" => "No se pudo enviar el correo. Error: " . $mail->ErrorInfo];
        }
    }

    public function enviarCorreoRecuperacion($email, $token_recuperacion)
    {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            require_once '../PHPMailer/src/PHPMailer.php';
            require_once '../PHPMailer/src/SMTP.php';
            require_once '../PHPMailer/src/Exception.php';
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'florrolito@gmail.com';
            $mail->Password = 'soez kekb uxzb umac';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('florrolito@gmail.com', 'Foro Virtual');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body = "
                <h1>Recuperación de Contraseña</h1>
                <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el enlace a continuación para establecer una nueva contraseña:</p>
                <a href='http://localhost/finalprogramacion/login/cambiar_password.php?token=$token_recuperacion'>Restablecer Contraseña</a>
                <p>Si no solicitaste este cambio, ignora este correo.</p>
            ";

            $mail->send();
            return ["status" => "success", "message" => "Correo de recuperación enviado correctamente."];
        } catch (Exception $e) {
            return ["status" => "error", "message" => "No se pudo enviar el correo. Error: " . $mail->ErrorInfo];
        }
    }

    public function registrarLog($accion, $id_usuario = null, $ip_origen = null)
    {
        if (!$ip_origen) {
            $ip_origen = $this->obtenerIP(); // usa método interno
        }

        $query = "INSERT INTO logs (id_usuario, accion, fecha_hora, ip_origen)
                VALUES (:id_usuario, :accion, NOW(), :ip_origen)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':ip_origen', $ip_origen);
        $stmt->execute();
    }

    private function obtenerIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
}
