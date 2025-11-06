<?php

// Script para procesar emails pendientes (ejecutar cada minuto)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$tempDir = __DIR__ . '/temp';

if (!is_dir($tempDir)) {
    exit(0);
}

// Buscar archivos pendientes
$files = glob($tempDir . '/contact_*.json');

if (empty($files)) {
    exit(0);
}

// Clase SMTP
class EmailProcessor {
    private $config;
    
    public function __construct() {
        $this->config = [
            'host' => 'a0020110.ferozo.com',
            'port' => 465,
            'username' => 'contacto@ldxsoftware.com.pe',
            'password' => 'R/zOx1Ao',
            'from_email' => 'contacto@ldxsoftware.com.pe',
            'from_name' => 'LDX Software'
        ];
    }
    
    private function sendSMTP($to, $subject, $body, $replyTo = null) {
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);

            $smtp = stream_socket_client(
                "ssl://{$this->config['host']}:{$this->config['port']}", 
                $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context
            );

            if (!$smtp) return false;

            // Leer respuesta multi-línea
            $readResponse = function() use ($smtp) {
                $response = '';
                do {
                    $line = fgets($smtp, 515);
                    $response .= $line;
                } while (isset($line[3]) && $line[3] === '-');
                return trim($response);
            };

            $sendCommand = function($command) use ($smtp, $readResponse) {
                fwrite($smtp, $command . "\r\n");
                return $readResponse();
            };

            // Protocolo SMTP
            $response = $readResponse();
            if (substr($response, 0, 3) !== '220') return false;

            $response = $sendCommand("EHLO localhost");
            if (substr($response, 0, 3) !== '250') return false;

            $response = $sendCommand("AUTH LOGIN");
            if (substr($response, 0, 3) !== '334') return false;

            $response = $sendCommand(base64_encode($this->config['username']));
            if (substr($response, 0, 3) !== '334') return false;

            $response = $sendCommand(base64_encode($this->config['password']));
            if (substr($response, 0, 3) !== '235') return false;

            $response = $sendCommand("MAIL FROM: <{$this->config['from_email']}>");
            if (substr($response, 0, 3) !== '250') return false;

            $response = $sendCommand("RCPT TO: <$to>");
            if (substr($response, 0, 3) !== '250') return false;

            $response = $sendCommand("DATA");
            if (substr($response, 0, 3) !== '354') return false;

            $headers = [
                "From: {$this->config['from_name']} <{$this->config['from_email']}>",
                "To: <$to>",
                "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
                "MIME-Version: 1.0",
                "Content-Type: text/html; charset=UTF-8",
                "Date: " . date('r'),
                "Message-ID: <" . uniqid() . "@localhost>"
            ];

            if ($replyTo) $headers[] = "Reply-To: $replyTo";

            $emailContent = implode("\r\n", $headers) . "\r\n\r\n" . $body;
            fwrite($smtp, $emailContent . "\r\n.\r\n");
            
            $response = $readResponse();
            if (substr($response, 0, 3) !== '250') return false;

            $sendCommand("QUIT");
            fclose($smtp);
            return true;

        } catch (Exception $e) {
            if (isset($smtp) && is_resource($smtp)) {
                fclose($smtp);
            }
            return false;
        }
    }
    
    public function processContact($data) {
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $email = $data['email'];
        $telefono = $data['telefono'];
        $mensaje = $data['mensaje'];

        // Email para admin
        $adminSubject = 'Nuevo mensaje de contacto - LDX Software';
        $adminBody = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px;'>
                <div style='background: #000; color: white; padding: 20px; text-align: center;'>
                    <h1>Nuevo Mensaje de Contacto</h1>
                    <p>LDX Software</p>
                </div>
                <div style='padding: 20px;'>
                    <p><strong>Nombre:</strong> " . htmlspecialchars($nombre . ' ' . $apellido) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                    <p><strong>Teléfono:</strong> " . htmlspecialchars($telefono) . "</p>
                    <p><strong>Mensaje:</strong></p>
                    <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
                </div>
                <div style='background: #f5f5f5; padding: 15px; text-align: center; font-size: 12px;'>
                    <p>Fecha: " . date('d/m/Y H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>";

        // Email de confirmación
        $confirmSubject = 'Confirmación de mensaje enviado - LDX Software';
        $confirmBody = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333; background: white;'>
            <div style='max-width: 600px; margin: 0 auto; border: 2px solid #e9ecef; border-radius: 8px;'>
                <div style='background: white; color: #333; padding: 30px; text-align: center; border-bottom: 3px solid #59FF27;'>
                    <h1>¡Mensaje Recibido!</h1>
                    <p>Gracias por contactarnos</p>
                </div>
                <div style='padding: 30px; background: white;'>
                    <p>Hola <strong>" . htmlspecialchars($nombre) . "</strong>,</p>
                    <p>Hemos recibido tu mensaje y te contactaremos pronto.</p>
                    
                    <div style='background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <p><strong>📧 Importante:</strong> Si no ves nuestra respuesta, <strong>revisa tu carpeta de spam</strong>.</p>
                    </div>
                    
                    <div style='background: white; border: 2px solid #59FF27; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <p><strong>Tu mensaje:</strong></p>
                        <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
                    </div>
                    
                    <div style='background: white; border: 2px solid #333; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='color: #59FF27; margin: 0 0 15px 0;'>Contacto</h3>
                        <p><strong>Email:</strong> contacto@ldxsoftware.com.pe</p>
                        <p><strong>WhatsApp:</strong> +51 905 940 757</p>
                    </div>
                    
                    <p>¡Gracias por confiar en <span style='color: #59FF27; font-weight: bold;'>LDX Software</span>!</p>
                </div>
            </div>
        </body>
        </html>";

        // Enviar emails
        $adminSent = $this->sendSMTP($this->config['username'], $adminSubject, $adminBody, "$nombre $apellido <$email>");
        $confirmSent = $this->sendSMTP($email, $confirmSubject, $confirmBody, $this->config['from_email']);

        return $adminSent && $confirmSent;
    }
}

// Procesar archivos
$processor = new EmailProcessor();

foreach ($files as $file) {
    try {
        $data = json_decode(file_get_contents($file), true);
        
        if ($data && $processor->processContact($data)) {
            unlink($file);
            error_log("Email procesado exitosamente: " . $data['email']);
        } else {
            // Si falla, mantener el archivo para reintento
            error_log("Error procesando email: " . $file);
        }
        
    } catch (Exception $e) {
        error_log("Error en email-processor: " . $e->getMessage());
    }
}

exit(0);
?>
