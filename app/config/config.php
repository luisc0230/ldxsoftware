<?php

return [
    // Configuración de email SMTP
    'email' => [
        'smtp_host' => 'a0020110.ferozo.com',
        'smtp_port' => 465, // Puerto SMTP para SSL
        'smtp_secure' => 'ssl', // SSL/TLS para puerto 465
        'smtp_username' => 'contacto@ldxsoftware.com.pe',
        'smtp_password' => 'R/zOx1Ao', // COLOCAR AQUÍ TU CONTRASEÑA
        'from_email' => 'contacto@ldxsoftware.com.pe',
        'from_name' => 'LDX Software',
        'admin_email' => 'contacto@ldxsoftware.com.pe',
        // Configuración IMAP para recibir correos (opcional)
        'imap_host' => 'a0020110.ferozo.com',
        'imap_port' => 993,
        'pop3_host' => 'a0020110.ferozo.com',
        'pop3_port' => 995
    ],
    
    // Configuración general
    'app' => [
        'name' => 'LDX Software',
        'url' => 'https://ldxsoftware.com.pe',
        'timezone' => 'America/Lima'
    ],
    
    // Configuración de seguridad
    'security' => [
        'csrf_token_name' => 'csrf_token',
        'max_attempts' => 5, // Máximo intentos de envío por IP
        'rate_limit_minutes' => 60 // Tiempo de bloqueo en minutos
    ]
];
