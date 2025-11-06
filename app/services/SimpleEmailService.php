<?php

namespace LDX\Services;

class SimpleEmailService
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function sendContactForm($data)
    {
        try {
            // Validar datos
            $this->validateContactData($data);

            // Preparar el email
            $to = $this->config['email']['admin_email'];
            $subject = 'Nuevo mensaje de contacto - ' . $this->config['app']['name'];
            $message = $this->buildContactEmailBody($data);
            
            // Headers para HTML
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ' . $this->config['email']['from_name'] . ' <' . $this->config['email']['from_email'] . '>',
                'Reply-To: ' . $data['nombre'] . ' ' . $data['apellido'] . ' <' . $data['email'] . '>',
                'X-Mailer: PHP/' . phpversion()
            ];

            // Enviar email usando mail() de PHP
            $result = mail($to, $subject, $message, implode("\r\n", $headers));

            if ($result) {
                // Log del envío exitoso
                error_log("Mensaje de contacto enviado desde: " . $data['email']);
                
                return [
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente'
                ];
            } else {
                throw new \Exception("Error al enviar el email");
            }

        } catch (\Exception $e) {
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
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email inválido");
        }

        if (strlen($data['mensaje']) > 500) {
            throw new \Exception("El mensaje no puede exceder 500 caracteres");
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
