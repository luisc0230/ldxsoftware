<?php

// Configurar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Configurar headers
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Limpiar buffer
if (ob_get_level()) {
    ob_clean();
}

// Clase SMTP que funciona
class WorkingSMTPMailer {
    private $host, $port, $username, $password, $from_email, $from_name;
    
    public function __construct($config) {
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->from_email = $config['from_email'];
        $this->from_name = $config['from_name'];
    }
    
    private function readResponse($smtp) {
        $response = '';
        do {
            $line = fgets($smtp, 515);
            $response .= $line;
        } while (isset($line[3]) && $line[3] === '-');
        return trim($response);
    }
    
    private function sendCommand($smtp, $command) {
        fwrite($smtp, $command . "\r\n");
        return $this->readResponse($smtp);
    }
    
    public function send($to, $subject, $body, $replyTo = null) {
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'ciphers' => 'DEFAULT:!DH'
                ]
            ]);

            $smtp = stream_socket_client(
                "ssl://{$this->host}:{$this->port}", 
                $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context
            );

            if (!$smtp) throw new Exception("Conexión SMTP falló: $errstr ($errno)");

            $response = $this->readResponse($smtp);
            if (substr($response, 0, 3) !== '220') throw new Exception("Saludo SMTP inválido");

            $response = $this->sendCommand($smtp, "EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
            if (substr($response, 0, 3) !== '250') throw new Exception("EHLO falló");

            $response = $this->sendCommand($smtp, "AUTH LOGIN");
            if (substr($response, 0, 3) !== '334') throw new Exception("AUTH LOGIN falló");

            $response = $this->sendCommand($smtp, base64_encode($this->username));
            if (substr($response, 0, 3) !== '334') throw new Exception("Username falló");

            $response = $this->sendCommand($smtp, base64_encode($this->password));
            if (substr($response, 0, 3) !== '235') throw new Exception("Password falló");

            $response = $this->sendCommand($smtp, "MAIL FROM: <{$this->from_email}>");
            if (substr($response, 0, 3) !== '250') throw new Exception("MAIL FROM falló");

            $response = $this->sendCommand($smtp, "RCPT TO: <$to>");
            if (substr($response, 0, 3) !== '250') throw new Exception("RCPT TO falló");

            $response = $this->sendCommand($smtp, "DATA");
            if (substr($response, 0, 3) !== '354') throw new Exception("DATA falló");

            $headers = [
                "From: {$this->from_name} <{$this->from_email}>",
                "To: <$to>",
                "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
                "MIME-Version: 1.0",
                "Content-Type: text/html; charset=UTF-8",
                "Content-Transfer-Encoding: quoted-printable",
                "Date: " . date('r'),
                "Message-ID: <" . uniqid() . "@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ">",
                "X-Mailer: WorkingSMTPMailer"
            ];

            if ($replyTo) $headers[] = "Reply-To: $replyTo";

            $emailContent = implode("\r\n", $headers) . "\r\n\r\n" . quoted_printable_encode($body);
            fwrite($smtp, $emailContent . "\r\n.\r\n");
            
            $response = $this->readResponse($smtp);
            if (substr($response, 0, 3) !== '250') throw new Exception("Envío falló");

            $this->sendCommand($smtp, "QUIT");
            fclose($smtp);
            return true;

        } catch (Exception $e) {
            if (isset($smtp) && is_resource($smtp)) {
                fclose($smtp);
            }
            throw $e;
        }
    }
}

// Respuesta por defecto
$response = ['success' => false, 'message' => 'Error desconocido', 'type' => 'error'];

try {
    // Obtener datos
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }

    if (empty($input)) {
        throw new Exception('No se recibieron datos');
    }

    // Validar datos
    $nombre = trim($input['nombre'] ?? '');
    $apellido = trim($input['apellido'] ?? '');
    $email = trim($input['email'] ?? '');
    $telefono = trim($input['telefono'] ?? '');
    $mensaje = trim($input['mensaje'] ?? '');

    // Validaciones
    if (empty($nombre)) throw new Exception('El nombre es requerido');
    if (empty($apellido)) throw new Exception('El apellido es requerido');
    if (empty($email)) throw new Exception('El email es requerido');
    if (empty($telefono)) throw new Exception('El teléfono es requerido');
    if (empty($mensaje)) throw new Exception('El mensaje es requerido');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }

    if (strlen($mensaje) > 500) {
        throw new Exception('El mensaje no puede exceder 500 caracteres');
    }

    // Configuración SMTP
    $smtpConfig = [
        'host' => 'a0020110.ferozo.com',
        'port' => 465,
        'username' => 'contacto@ldxsoftware.com.pe',
        'password' => 'R/zOx1Ao',
        'from_email' => 'contacto@ldxsoftware.com.pe',
        'from_name' => 'LDX Software'
    ];

    $mailer = new WorkingSMTPMailer($smtpConfig);

    // Email para administrador
    $subject = 'Nuevo mensaje de contacto - LDX Software';
    $replyTo = "$nombre $apellido <$email>";
    
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Nuevo mensaje de contacto</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .header { background: #000; color: white; padding: 30px 20px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .header p { margin: 10px 0 0 0; opacity: 0.9; }
            .content { padding: 30px 20px; }
            .field { margin-bottom: 20px; }
            .label { font-weight: bold; color: #555; margin-bottom: 8px; display: block; }
            .value { padding: 12px; background: #f8f9fa; border-left: 4px solid #59FF27; border-radius: 4px; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #eee; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Nuevo Mensaje de Contacto</h1>
                <p>LDX Software</p>
            </div>
            
            <div class='content'>
                <div class='field'>
                    <span class='label'>Nombre Completo:</span>
                    <div class='value'>" . htmlspecialchars($nombre . ' ' . $apellido) . "</div>
                </div>
                
                <div class='field'>
                    <span class='label'>Email:</span>
                    <div class='value'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
                </div>
                
                <div class='field'>
                    <span class='label'>Teléfono:</span>
                    <div class='value'>" . htmlspecialchars($telefono) . "</div>
                </div>
                
                <div class='field'>
                    <span class='label'>Mensaje:</span>
                    <div class='value'>" . nl2br(htmlspecialchars($mensaje)) . "</div>
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>Enviado desde el formulario de contacto de LDX Software</strong></p>
                <p>Fecha: " . date('d/m/Y H:i:s') . "</p>
                <p>Para responder, simplemente contesta a este email.</p>
            </div>
        </div>
    </body>
    </html>";

    // Enviar email al administrador
    $mailer->send($smtpConfig['username'], $subject, $emailBody, $replyTo);

    // Email de confirmación para el cliente
    $confirmationSubject = 'Confirmación de mensaje enviado - LDX Software';
    $confirmationBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Confirmación de mensaje</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background: white; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 2px solid #e9ecef; }
            .header { background: white; color: #333; padding: 30px 20px; text-align: center; border-bottom: 3px solid #59FF27; }
            .header h1 { margin: 0; font-size: 24px; color: #333; }
            .header p { margin: 10px 0 0 0; color: #666; }
            .content { padding: 30px 20px; background: white; }
            .message-box { background: white; padding: 20px; border-radius: 8px; border: 2px solid #59FF27; margin: 20px 0; }
            .footer { background: white; padding: 20px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #eee; }
            .contact-info { background: white; border: 2px solid #333; color: #333; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .contact-info h3 { margin: 0 0 15px 0; color: #59FF27; font-weight: bold; }
            .highlight { color: #59FF27; font-weight: bold; }
            .spam-notice { background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>¡Mensaje Recibido!</h1>
                <p>Gracias por contactarnos</p>
            </div>
            
            <div class='content'>
                <p>Hola <strong>" . htmlspecialchars($nombre) . "</strong>,</p>
                
                <p>Hemos recibido tu mensaje y te contactaremos lo más pronto posible. A continuación, una copia de la información que nos enviaste:</p>
                
                <div class='spam-notice'>
                    <p><strong>📧 Importante:</strong> Si no ves nuestra respuesta en tu bandeja de entrada, <strong>revisa tu carpeta de spam o correo no deseado</strong>. A veces nuestros emails pueden llegar ahí.</p>
                </div>
                
                <div class='message-box'>
                    <p><strong>Nombre:</strong> " . htmlspecialchars($nombre . ' ' . $apellido) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                    <p><strong>Teléfono:</strong> " . htmlspecialchars($telefono) . "</p>
                    <p><strong>Mensaje:</strong></p>
                    <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
                </div>
                
                <div class='contact-info'>
                    <h3>Información de Contacto</h3>
                    <p><strong>Email:</strong> contacto@ldxsoftware.com.pe</p>
                    <p><strong>WhatsApp:</strong> +51 905 940 757</p>
                    <p><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                </div>
                
                <p>Normalmente respondemos en un plazo de 24 horas. Si tu consulta es urgente, no dudes en contactarnos directamente por WhatsApp.</p>
                
                <p>¡Gracias por confiar en <span class='highlight'>LDX Software</span>!</p>
            </div>
            
            <div class='footer'>
                <p><strong>LDX Software - Innovación en Software</strong></p>
                <p>Este es un mensaje automático, por favor no respondas a este email.</p>
                <p>Fecha: " . date('d/m/Y H:i:s') . "</p>
            </div>
        </div>
    </body>
    </html>";

    // Enviar confirmación al cliente
    $mailer->send($email, $confirmationSubject, $confirmationBody, $smtpConfig['from_email']);

    $response = [
        'success' => true,
        'message' => '¡Mensaje enviado correctamente! Revisa tu bandeja de entrada y carpeta de spam para la confirmación.',
        'type' => 'success'
    ];
    
    error_log("Email enviado exitosamente desde: $email a " . $smtpConfig['username']);

} catch (Exception $e) {
    error_log("Error en contact-working.php: " . $e->getMessage());
    
    $response = [
        'success' => false,
        'message' => 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.',
        'type' => 'error'
    ];
}

// Respuesta JSON
$jsonResponse = json_encode($response, JSON_UNESCAPED_UNICODE);
if ($jsonResponse === false) {
    $jsonResponse = json_encode([
        'success' => false,
        'message' => 'Error procesando respuesta',
        'type' => 'error'
    ]);
}

echo $jsonResponse;
exit;
?>
