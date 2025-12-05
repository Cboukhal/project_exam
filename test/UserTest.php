<?php
//On va utiliser le framework phpunit
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $pdo;

    /**
     * Exécuté AVANT chaque test
     */
    protected function setUp(): void
    {
        // Créer une base de données de test en mémoire (SQLite)
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Créer la structure de la table users
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                civilite VARCHAR(3),
                prenom VARCHAR(50) NOT NULL,
                nom VARCHAR(50) NOT NULL,
                mail VARCHAR(100) UNIQUE NOT NULL,
                mdp VARCHAR(255) NOT NULL,
                role VARCHAR(20) DEFAULT 'user',
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /**
     * Test d'insertion d'un utilisateur
     */
    public function testCreerUtilisateur()
    {
        $sql = "INSERT INTO users (civilite, prenom, nom, mail, mdp, role) 
                VALUES (:civilite, :prenom, :nom, :mail, :mdp, :role)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':civilite' => 'M.',
            ':prenom' => 'Jean',
            ':nom' => 'Dupont',
            ':mail' => 'jean.dupont@test.com',
            ':mdp' => password_hash('password123', PASSWORD_DEFAULT),
            ':role' => 'user'
        ]);
        
        $this->assertTrue($result);
        $this->assertEquals(1, $this->pdo->lastInsertId());
    }

    /**
     * Test de recherche d'un utilisateur par email
     */
    public function testRechercherUtilisateurParEmail()
    {
        // Insérer un utilisateur de test
        $this->pdo->exec("
            INSERT INTO users (civilite, prenom, nom, mail, mdp, role) 
            VALUES ('Mme', 'Marie', 'Martin', 'marie@test.com', 'hash123', 'user')
        ");
        
        // Rechercher l'utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE mail = :mail");
        $stmt->execute([':mail' => 'marie@test.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($user);
        $this->assertEquals('Marie', $user['prenom']);
        $this->assertEquals('Martin', $user['nom']);
        $this->assertEquals('marie@test.com', $user['mail']);
    }

    /**
     * Test de contrainte UNIQUE sur l'email
     */
    public function testEmailDuplique()
    {
        $this->expectException(PDOException::class);
        
        // Insérer le premier utilisateur
        $this->pdo->exec("
            INSERT INTO users (prenom, nom, mail, mdp) 
            VALUES ('Test', 'User', 'duplicate@test.com', 'hash')
        ");
        
        // Tenter d'insérer un email dupliqué (doit lancer une exception)
        $this->pdo->exec("
            INSERT INTO users (prenom, nom, mail, mdp) 
            VALUES ('Test2', 'User2', 'duplicate@test.com', 'hash2')
        ");
    }

    /**
     * Test de validation de mot de passe
     */
    public function testVerificationMotDePasse()
    {
        $password = 'MonMotDePasseSecurise123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insérer utilisateur
        $this->pdo->exec("
            INSERT INTO users (prenom, nom, mail, mdp) 
            VALUES ('Test', 'Password', 'test@pass.com', '$hash')
        ");
        
        // Récupérer et vérifier
        $stmt = $this->pdo->query("SELECT mdp FROM users WHERE mail = 'test@pass.com'");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertTrue(password_verify($password, $user['mdp']));
        $this->assertFalse(password_verify('MauvaisMotDePasse', $user['mdp']));
    }

    /**
     * Exécuté APRÈS chaque test (nettoyage)
     */
    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}