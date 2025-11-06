<?php

namespace LDX\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $config;
    private $mailer;

    public function __construct($config)
    {
        $this->config = $config;
        $this->setupMailer();
    }

    private function setupMailer()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['email']['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['email']['smtp_username'];
            $this->mailer->Password = $this->config['email']['smtp_password'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mailer->Port = $this->config['email']['smtp_port'];

            // Configuración del remitente
            $this->mailer->setFrom(
                $this->config['email']['from_email'], 
                $this->config['email']['from_name']
            );

            // Configuración de codificación
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->isHTML(true);

        } catch (Exception $e) {
            error_log("Error configurando PHPMailer: " . $e->getMessage());
            throw new Exception("Error en la configuración del correo");
        }
    }

    public function sendContactForm($data)
    {
        try {
            // Validar datos
            $this->validateContactData($data);

            // Configurar destinatario
            $this->mailer->addAddress($this->config['email']['admin_email']);

            // Configurar respuesta
            $this->mailer->addReplyTo($data['email'], $data['nombre'] . ' ' . $data['apellido']);

            // Asunto
            $this->mailer->Subject = 'Nuevo mensaje de contacto - ' . $this->config['app']['name'];

            // Cuerpo del mensaje
            $this->mailer->Body = $this->buildContactEmailBody($data);

            // Enviar
            $result = $this->mailer->send();
            
            // Limpiar destinatarios para próximo uso
            $this->mailer->clearAddresses();
            $this->mailer->clearReplyTos();

            return [
                'success' => true,
                'message' => 'Mensaje enviado correctamente'
            ];

        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.'
            ];
        }
    }

    private function validateContactData($data)
    {
        $required = ['nombre', 'apellido', 'email', 'telefono', 'mensaje'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        if (strlen($data['mensaje']) > 500) {
            throw new Exception("El mensaje no puede exceder 500 caracteres");
        }
    }

    private function buildContactEmailBody($data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Nuevo mensaje de contacto</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #000; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .value { margin-top: 5px; padding: 10px; background: white; border-left: 4px solid #59FF27; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Nuevo Mensaje de Contacto</h1>
                    <p>' . $this->config['app']['name'] . '</p>
                </div>
                
                <div class="content">
                    <div class="field">
                        <div class="label">Nombre:</div>
                        <div class="value">' . htmlspecialchars($data['nombre']) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Apellido:</div>
                        <div class="value">' . htmlspecialchars($data['apellido']) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Email:</div>
                        <div class="value">' . htmlspecialchars($data['email']) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Teléfono:</div>
                        <div class="value">' . htmlspecialchars($data['telefono']) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Mensaje:</div>
                        <div class="value">' . nl2br(htmlspecialchars($data['mensaje'])) . '</div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Este mensaje fue enviado desde el formulario de contacto de ' . $this->config['app']['name'] . '</p>
                    <p>Fecha: ' . date('d/m/Y H:i:s') . '</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
