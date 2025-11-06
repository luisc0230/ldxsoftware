<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($page_title) ? escape($page_title) : escape($site_name); ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? escape($page_description) : escape($site_description); ?>">
    <meta name="keywords" content="<?php echo isset($page_keywords) ? escape($page_keywords) : 'software, desarrollo, web, aplicaciones, LDX'; ?>">
    <meta name="author" content="<?php echo escape($site_name); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? escape($page_title) : escape($site_name); ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? escape($page_description) : escape($site_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo escape($base_url); ?>">
    <meta property="og:image" content="<?php echo asset('images/og-image.jpg'); ?>">
    <meta property="og:site_name" content="<?php echo escape($site_name); ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($page_title) ? escape($page_title) : escape($site_name); ?>">
    <meta name="twitter:description" content="<?php echo isset($page_description) ? escape($page_description) : escape($site_description); ?>">
    <meta name="twitter:image" content="<?php echo asset('images/twitter-card.jpg'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('images/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo asset('images/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo asset('images/favicon-16x16.png'); ?>">
    <link rel="manifest" href="<?php echo asset('images/site.webmanifest'); ?>">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        accent: {
                            50: '#fef7ff',
                            100: '#fce7ff',
                            200: '#f8d4fe',
                            300: '#f0abfc',
                            400: '#e879f9',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                            800: '#86198f',
                            900: '#701a75',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'heading': ['Poppins', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'fade-in-up': 'fadeInUp 0.6s ease-out',
                        'fade-in-down': 'fadeInDown 0.6s ease-out',
                        'slide-in-left': 'slideInLeft 0.6s ease-out',
                        'slide-in-right': 'slideInRight 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        bounceIn: {
                            '0%': { opacity: '0', transform: 'scale(0.3)' },
                            '50%': { opacity: '1', transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        
        .font-heading {
            font-family: 'Poppins', system-ui, sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .section-padding {
            padding: 5rem 0;
        }
        
        @media (max-width: 768px) {
            .section-padding {
                padding: 3rem 0;
            }
        }
        
        /* Loading animation */
        .loading {
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
    
    <!-- Additional CSS files -->
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css_file): ?>
            <link rel="stylesheet" href="<?php echo asset('css/' . $css_file); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php echo escape($site_name); ?>",
        "url": "<?php echo escape($base_url); ?>",
        "logo": "<?php echo asset('images/logo.png'); ?>",
        "description": "<?php echo escape($site_description); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Av. Tecnológico 123, Col. Innovación",
            "addressLocality": "Ciudad de México",
            "postalCode": "01234",
            "addressCountry": "MX"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+52-55-1234-5678",
            "contactType": "customer service",
            "availableLanguage": ["Spanish", "English"]
        },
        "sameAs": [
            "https://facebook.com/ldxsoftware",
            "https://twitter.com/ldxsoftware",
            "https://linkedin.com/company/ldxsoftware",
            "https://github.com/ldxsoftware"
        ]
    }
    </script>
</head>
