<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar el archivo .env con las credenciales
loadEnv(__DIR__ . '/.env');

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir las clases de PHPMailer
    require './mailer/PHPMailer.php';
    require './mailer/SMTP.php';
    require './mailer/Exception.php';

    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurar el servidor SMTP utilizando variables de entorno
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER'); // Tu dirección de correo electrónico
        $mail->Password = getenv('SMTP_PASS'); // Tu contraseña (o mejor, App Password de Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv('SMTP_PORT');

        // Configurar el correo
        $mail->setFrom(getenv('SMTP_USER'), 'Nombre Remitente');
        $mail->addAddress($correo, $nombre);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Asunto del mensaje';
        $mail->Body = 'Este es el mensaje: ' . $mensaje;

        // Enviar el correo
        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
