<?php
/**
 * LDX Software - Contact Model
 * 
 * Manages contact form submissions and newsletter subscriptions
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

class ContactModel {
    
    private $contactsFile;
    private $newsletterFile;
    
    public function __construct() {
        // Define file paths for storing data
        $dataDir = dirname(__DIR__) . '/data/';
        
        // Create data directory if it doesn't exist
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        
        $this->contactsFile = $dataDir . 'contacts.json';
        $this->newsletterFile = $dataDir . 'newsletter.json';
        
        // Initialize files if they don't exist
        $this->initializeFiles();
    }
    
    /**
     * Initialize data files
     */
    private function initializeFiles() {
        if (!file_exists($this->contactsFile)) {
            file_put_contents($this->contactsFile, json_encode([]));
        }
        
        if (!file_exists($this->newsletterFile)) {
            file_put_contents($this->newsletterFile, json_encode([]));
        }
    }
    
    /**
     * Save contact form submission
     */
    public function saveContact($data) {
        try {
            // Load existing contacts
            $contacts = $this->loadContacts();
            
            // Generate unique ID
            $contactId = uniqid('contact_', true);
            
            // Prepare contact data
            $contact = [
                'id' => $contactId,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'company' => $data['company'] ?? '',
                'service' => $data['service'] ?? '',
                'budget' => $data['budget'] ?? '',
                'message' => $data['message'],
                'newsletter' => $data['newsletter'] ?? false,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'new',
                'read' => false
            ];
            
            // Add to contacts array
            $contacts[] = $contact;
            
            // Save to file
            if (file_put_contents($this->contactsFile, json_encode($contacts, JSON_PRETTY_PRINT))) {
                // If newsletter subscription is requested, save it separately
                if ($data['newsletter']) {
                    $this->saveNewsletter($data['email'], $data['name']);
                }
                
                return $contactId;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error saving contact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Save newsletter subscription
     */
    public function saveNewsletter($email, $name = '') {
        try {
            // Load existing subscriptions
            $subscribers = $this->loadNewsletterSubscribers();
            
            // Check if email already exists
            foreach ($subscribers as $subscriber) {
                if ($subscriber['email'] === $email) {
                    return true; // Already subscribed
                }
            }
            
            // Prepare subscriber data
            $subscriber = [
                'id' => uniqid('newsletter_', true),
                'email' => $email,
                'name' => $name,
                'subscribed_at' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'status' => 'active',
                'confirmed' => false
            ];
            
            // Add to subscribers array
            $subscribers[] = $subscriber;
            
            // Save to file
            return file_put_contents($this->newsletterFile, json_encode($subscribers, JSON_PRETTY_PRINT)) !== false;
            
        } catch (Exception $e) {
            error_log("Error saving newsletter subscription: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Load all contacts
     */
    public function loadContacts() {
        try {
            if (file_exists($this->contactsFile)) {
                $content = file_get_contents($this->contactsFile);
                return json_decode($content, true) ?: [];
            }
            return [];
        } catch (Exception $e) {
            error_log("Error loading contacts: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Load newsletter subscribers
     */
    public function loadNewsletterSubscribers() {
        try {
            if (file_exists($this->newsletterFile)) {
                $content = file_get_contents($this->newsletterFile);
                return json_decode($content, true) ?: [];
            }
            return [];
        } catch (Exception $e) {
            error_log("Error loading newsletter subscribers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get contact by ID
     */
    public function getContactById($id) {
        $contacts = $this->loadContacts();
        
        foreach ($contacts as $contact) {
            if ($contact['id'] === $id) {
                return $contact;
            }
        }
        
        return null;
    }
    
    /**
     * Get recent contacts
     */
    public function getRecentContacts($limit = 10) {
        $contacts = $this->loadContacts();
        
        // Sort by created_at (newest first)
        usort($contacts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($contacts, 0, $limit);
    }
    
    /**
     * Get unread contacts count
     */
    public function getUnreadContactsCount() {
        $contacts = $this->loadContacts();
        
        return count(array_filter($contacts, function($contact) {
            return !$contact['read'];
        }));
    }
    
    /**
     * Mark contact as read
     */
    public function markContactAsRead($id) {
        try {
            $contacts = $this->loadContacts();
            
            foreach ($contacts as &$contact) {
                if ($contact['id'] === $id) {
                    $contact['read'] = true;
                    $contact['read_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            
            return file_put_contents($this->contactsFile, json_encode($contacts, JSON_PRETTY_PRINT)) !== false;
            
        } catch (Exception $e) {
            error_log("Error marking contact as read: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update contact status
     */
    public function updateContactStatus($id, $status) {
        try {
            $contacts = $this->loadContacts();
            
            foreach ($contacts as &$contact) {
                if ($contact['id'] === $id) {
                    $contact['status'] = $status;
                    $contact['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            
            return file_put_contents($this->contactsFile, json_encode($contacts, JSON_PRETTY_PRINT)) !== false;
            
        } catch (Exception $e) {
            error_log("Error updating contact status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get contacts statistics
     */
    public function getContactsStats() {
        $contacts = $this->loadContacts();
        $subscribers = $this->loadNewsletterSubscribers();
        
        $stats = [
            'total_contacts' => count($contacts),
            'unread_contacts' => $this->getUnreadContactsCount(),
            'newsletter_subscribers' => count($subscribers),
            'contacts_this_month' => 0,
            'contacts_this_week' => 0,
            'popular_services' => []
        ];
        
        // Calculate monthly and weekly contacts
        $now = time();
        $monthStart = strtotime('first day of this month');
        $weekStart = strtotime('monday this week');
        
        $services = [];
        
        foreach ($contacts as $contact) {
            $contactTime = strtotime($contact['created_at']);
            
            if ($contactTime >= $monthStart) {
                $stats['contacts_this_month']++;
            }
            
            if ($contactTime >= $weekStart) {
                $stats['contacts_this_week']++;
            }
            
            // Count services
            if (!empty($contact['service'])) {
                $service = $contact['service'];
                $services[$service] = ($services[$service] ?? 0) + 1;
            }
        }
        
        // Sort services by popularity
        arsort($services);
        $stats['popular_services'] = array_slice($services, 0, 5, true);
        
        return $stats;
    }
    
    /**
     * Search contacts
     */
    public function searchContacts($query) {
        $contacts = $this->loadContacts();
        $query = strtolower($query);
        
        return array_filter($contacts, function($contact) use ($query) {
            return strpos(strtolower($contact['name']), $query) !== false ||
                   strpos(strtolower($contact['email']), $query) !== false ||
                   strpos(strtolower($contact['company']), $query) !== false ||
                   strpos(strtolower($contact['message']), $query) !== false;
        });
    }
    
    /**
     * Export contacts to CSV
     */
    public function exportContactsToCSV() {
        $contacts = $this->loadContacts();
        
        $csv = "ID,Nombre,Email,Teléfono,Empresa,Servicio,Presupuesto,Mensaje,Newsletter,Fecha,Estado\n";
        
        foreach ($contacts as $contact) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $contact['id'],
                $contact['name'],
                $contact['email'],
                $contact['phone'],
                $contact['company'],
                $contact['service'],
                $contact['budget'],
                str_replace('"', '""', $contact['message']),
                $contact['newsletter'] ? 'Sí' : 'No',
                $contact['created_at'],
                $contact['status']
            );
        }
        
        return $csv;
    }
}
