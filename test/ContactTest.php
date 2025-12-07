<?php

use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    //seule cette classe peut accéder à cette propriétée pour protéger la connexion 
    private $pdo;

    //protected = méthode spéciale pour initialiser chaque test
    protected function setUp(): void
    {
        // PDO SQLite en mémoire pour les tests isolés
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
         // Création de la table services (similaire à MySQL mais compatible SQLite)
        $this->pdo->exec("
            CREATE TABLE contact (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                prenom VARCHAR(100),
                nom VARCHAR(100),
                email VARCHAR(255) NOT NULL,
                sujet VARCHAR(255),
                message TEXT NOT NULL,
                status VARCHAR(20) DEFAULT 'new',
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function testEnvoiMessageContact()
    {
        $sql = "INSERT INTO contact (prenom, nom, email, sujet, message, status) 
                VALUES (:prenom, :nom, :email, :sujet, :message, 'new')";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':prenom' => 'Jean',
            ':nom' => 'Dupont',
            ':email' => 'jean@test.com',
            ':sujet' => 'Demande de devis',
            ':message' => 'Je souhaite obtenir un devis pour une installation électrique.'
        ]);
        
        $this->assertTrue($result);
        
        // Vérifier que le message est bien en BDD
        $stmt = $this->pdo->query("SELECT * FROM contact WHERE email = 'jean@test.com'");
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals('Jean', $contact['prenom']);
        $this->assertEquals('new', $contact['status']);
    }

    public function testValidationEmailContact()
    {
        $emails_valides = [
            'test@example.com',
            'user.name@example.co.uk',
            'user+tag@example.com'
        ];
        
        foreach ($emails_valides as $email) {
            $this->assertNotFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL),
                "L'email $email devrait être valide"
            );
        }
    }

    public function testValidationLongueurMessage()
    {
        $message_trop_court = 'Court';
        $message_valide = 'Ceci est un message de longueur suffisante pour être valide';
        $message_trop_long = str_repeat('a', 1001);
        
        $this->assertFalse(strlen($message_trop_court) >= 10 && strlen($message_trop_court) <= 1000);
        $this->assertTrue(strlen($message_valide) >= 10 && strlen($message_valide) <= 1000);
        $this->assertFalse(strlen($message_trop_long) >= 10 && strlen($message_trop_long) <= 1000);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}