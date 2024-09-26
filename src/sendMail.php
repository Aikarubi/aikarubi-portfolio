<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir las clases de PHPMailer
require '../lib/phpMailer/PHPMailer.php';
require '../lib/phpMailer/SMTP.php';
require '../lib/phpMailer/Exception.php';
require './config.php';

// Verificar que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);
    var_dump(getenv('SMTP_USER')); // Esto debería mostrar tu dirección de correo electrónico
    var_dump(getenv('SMTP_HOST')); // Esto debería mostrar el host de tu SMTP
    
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv('SMTP_PORT');

        // Configuración del correo
        $mail->setFrom(getenv('SMTP_USER'), 'Formulario de Contacto');
        $mail->addAddress(getenv('SMTP_USER')); // Enviar el mensaje a tu correo

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo Mensaje de Contacto';

        // Plantilla HTML del mensaje
        $body = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Exo 2', sans-serif;
                    }
                    .container {
                        padding: 20px;
                        background-color: #f4f4f4;
                        border-radius: 10px;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    h2 {
                        color: #333;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.5;
                    }
                    .message {
                        background-color: #fff;
                        padding: 15px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Nuevo mensaje de contacto</h2>
                    <div class='message'>
                        <p><strong>Nombre:</strong> $nombre</p>
                        <p><strong>Correo Electrónico:</strong> $correo</p>
                        <p><strong>Mensaje:</strong><br>" . nl2br($mensaje) . "</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->Body = $body;

        // Enviar el correo
        $mail->send();
        echo 'El mensaje ha sido enviado exitosamente.';
        header('Location: ../index.html');
    } catch (Exception $e) {
        echo "Hubo un error al enviar el mensaje: {$mail->ErrorInfo}";
    }
} else {
    echo "Método no permitido.";
}
