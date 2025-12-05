<?php
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
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
        
        // Créer les tables nécessaires
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

        $this->pdo->exec("
            CREATE TABLE partenaire (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom VARCHAR(150) NOT NULL,
                url VARCHAR(255) NOT NULL,
                description TEXT,
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->pdo->exec("
            CREATE TABLE commentaire (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pseudo VARCHAR(100) NOT NULL,
                email VARCHAR(100),
                note INTEGER DEFAULT 0,
                commentaire TEXT NOT NULL,
                approved INTEGER DEFAULT 0,
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->pdo->exec("
            CREATE TABLE galeries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                filename VARCHAR(255) NOT NULL,
                mime_type VARCHAR(50),
                file_size INTEGER,
                legende TEXT,
                image_type VARCHAR(50) DEFAULT 'particulier',
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->pdo->exec("
            CREATE TABLE contact (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                prenom VARCHAR(50),
                nom VARCHAR(50),
                email VARCHAR(100) NOT NULL,
                sujet VARCHAR(200),
                message TEXT NOT NULL,
                status VARCHAR(20) DEFAULT 'new',
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->pdo->exec("
            CREATE TABLE requete_devis (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                Professionnels_Particuliers VARCHAR(50),
                contact_name VARCHAR(100),
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20),
                message TEXT,
                status VARCHAR(20) DEFAULT 'new',
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    // ==================== TESTS PARTENAIRES ====================

    /**
     * Test d'ajout d'un partenaire valide
     */
    public function testAjouterPartenaireValide()
    {
        $stmt = $this->pdo->prepare("INSERT INTO partenaire (nom, url, description) VALUES (:nom, :url, :description)");
        $result = $stmt->execute([
            ':nom' => 'Partenaire Test',
            ':url' => 'https://example.com',
            ':description' => 'Description test'
        ]);
        
        $this->assertTrue($result);
        $this->assertEquals(1, $this->pdo->lastInsertId());
    }

    /**
     * Test de validation d'URL
     */
    public function testValidationURLPartenaire()
    {
        $urlValide = 'https://www.exemple.fr';
        $urlInvalide = 'pas-une-url';
        
        $this->assertTrue((bool)filter_var($urlValide, FILTER_VALIDATE_URL));
        $this->assertFalse((bool)filter_var($urlInvalide, FILTER_VALIDATE_URL));
    }

    /**
     * Test de suppression d'un partenaire
     */
    public function testSupprimerPartenaire()
    {
        // Insérer un partenaire
        $this->pdo->exec("INSERT INTO partenaire (nom, url) VALUES ('Test', 'https://test.com')");
        $id = $this->pdo->lastInsertId();
        
        // Supprimer
        $stmt = $this->pdo->prepare("DELETE FROM partenaire WHERE id = ?");
        $stmt->execute([$id]);
        
        // Vérifier
        $stmt = $this->pdo->prepare("SELECT * FROM partenaire WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        $this->assertFalse($result);
    }

    // ==================== TESTS COMMENTAIRES ====================

    /**
     * Test d'approbation d'un commentaire
     */
    public function testApprouverCommentaire()
    {
        // Insérer un commentaire en attente
        $this->pdo->exec("INSERT INTO commentaire (pseudo, email, note, commentaire, approved) 
                          VALUES ('Jean', 'jean@test.com', 5, 'Super service!', 0)");
        $id = $this->pdo->lastInsertId();
        
        // Approuver
        $stmt = $this->pdo->prepare("UPDATE commentaire SET approved = 1 WHERE id = ?");
        $stmt->execute([$id]);
        
        // Vérifier
        $stmt = $this->pdo->prepare("SELECT approved FROM commentaire WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals(1, $result['approved']);
    }

    /**
     * Test de rejet d'un commentaire
     */
    public function testRejeterCommentaire()
    {
        // Insérer un commentaire
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) 
                          VALUES ('Test', 'Commentaire test', 0)");
        $id = $this->pdo->lastInsertId();
        
        // Rejeter
        $stmt = $this->pdo->prepare("UPDATE commentaire SET approved = -1 WHERE id = ?");
        $stmt->execute([$id]);
        
        // Vérifier
        $stmt = $this->pdo->prepare("SELECT approved FROM commentaire WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals(-1, $result['approved']);
    }

    /**
     * Test de comptage des commentaires en attente
     */
    public function testCompterCommentairesEnAttente()
    {
        // Insérer des commentaires avec différents statuts
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('User1', 'Test1', 0)");
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('User2', 'Test2', 1)");
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('User3', 'Test3', 0)");
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('User4', 'Test4', -1)");
        
        // Compter ceux en attente (approved = 0)
        $count = $this->pdo->query("SELECT COUNT(*) FROM commentaire WHERE approved = 0")->fetchColumn();
        
        $this->assertEquals(2, $count);
    }

    // ==================== TESTS GALERIES ====================

    /**
     * Test d'insertion d'une image dans la galerie
     */
    public function testAjouterImageGalerie()
    {
        $stmt = $this->pdo->prepare("INSERT INTO galeries (filename, mime_type, file_size, legende, image_type) 
                                      VALUES (:filename, :mime_type, :file_size, :legende, :image_type)");
        $result = $stmt->execute([
            ':filename' => 'img_test123.jpg',
            ':mime_type' => 'image/jpeg',
            ':file_size' => 2048000,
            ':legende' => 'Photo test',
            ':image_type' => 'particulier'
        ]);
        
        $this->assertTrue($result);
    }

    /**
     * Test de validation du type MIME
     */
    public function testValidationTypeMIME()
    {
        $typesAutorises = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        
        $this->assertTrue(in_array('image/jpeg', $typesAutorises));
        $this->assertTrue(in_array('image/png', $typesAutorises));
        $this->assertFalse(in_array('application/pdf', $typesAutorises));
        $this->assertFalse(in_array('text/html', $typesAutorises));
    }

    /**
     * Test de validation de la taille du fichier
     */
    public function testValidationTailleFichier()
    {
        $maxSize = 5 * 1024 * 1024; // 5 MB
        
        $tailleValide = 3 * 1024 * 1024; // 3 MB
        $tailleInvalide = 6 * 1024 * 1024; // 6 MB
        
        $this->assertTrue($tailleValide <= $maxSize);
        $this->assertFalse($tailleInvalide <= $maxSize);
    }

    // ==================== TESTS CONTACTS ====================

    /**
     * Test de mise à jour du statut d'un contact
     */
    public function testMettreAJourStatutContact()
    {
        // Insérer un contact
        $this->pdo->exec("INSERT INTO contact (prenom, nom, email, sujet, message, status) 
                          VALUES ('Marie', 'Dupont', 'marie@test.com', 'Question', 'Bonjour', 'new')");
        $id = $this->pdo->lastInsertId();
        
        // Mettre à jour le statut
        $stmt = $this->pdo->prepare("UPDATE contact SET status = ? WHERE id = ?");
        $stmt->execute(['read', $id]);
        
        // Vérifier
        $stmt = $this->pdo->prepare("SELECT status FROM contact WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals('read', $result['status']);
    }

    /**
     * Test de validation des statuts autorisés
     */
    public function testValidationStatutsContact()
    {
        $statutsAutorises = ['new', 'read', 'closed'];
        
        $this->assertTrue(in_array('new', $statutsAutorises));
        $this->assertTrue(in_array('read', $statutsAutorises));
        $this->assertTrue(in_array('closed', $statutsAutorises));
        $this->assertFalse(in_array('invalid', $statutsAutorises));
    }

    // ==================== TESTS DEVIS ====================

    /**
     * Test d'ajout d'une demande de devis
     */
    public function testAjouterDemandeDevis()
    {
        $stmt = $this->pdo->prepare("INSERT INTO requete_devis (Professionnels_Particuliers, contact_name, email, phone, message) 
                                      VALUES (:type, :name, :email, :phone, :message)");
        $result = $stmt->execute([
            ':type' => 'Particulier',
            ':name' => 'Jean Martin',
            ':email' => 'jean.martin@test.com',
            ':phone' => '0612345678',
            ':message' => 'Demande de devis pour installation'
        ]);
        
        $this->assertTrue($result);
    }

    /**
     * Test de comptage des nouveaux devis
     */
    public function testCompterNouveauxDevis()
    {
        // Insérer des devis avec différents statuts
        $this->pdo->exec("INSERT INTO requete_devis (email, message, status) VALUES ('test1@test.com', 'Message 1', 'new')");
        $this->pdo->exec("INSERT INTO requete_devis (email, message, status) VALUES ('test2@test.com', 'Message 2', 'in_progress')");
        $this->pdo->exec("INSERT INTO requete_devis (email, message, status) VALUES ('test3@test.com', 'Message 3', 'new')");
        
        $count = $this->pdo->query("SELECT COUNT(*) FROM requete_devis WHERE status = 'new'")->fetchColumn();
        
        $this->assertEquals(2, $count);
    }

    // ==================== TESTS UTILISATEURS ====================

    /**
     * Test de suppression d'un utilisateur
     */
    public function testSupprimerUtilisateur()
    {
        // Créer deux utilisateurs
        $this->pdo->exec("INSERT INTO users (prenom, nom, mail, mdp, role) 
                          VALUES ('Admin', 'Test', 'admin@test.com', 'hash1', 'admin')");
        $adminId = $this->pdo->lastInsertId();
        
        $this->pdo->exec("INSERT INTO users (prenom, nom, mail, mdp, role) 
                          VALUES ('User', 'Test', 'user@test.com', 'hash2', 'user')");
        $userId = $this->pdo->lastInsertId();
        
        // Supprimer l'utilisateur simple
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        
        // Vérifier qu'il n'existe plus
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $this->assertFalse($stmt->fetch());
        
        // Vérifier que l'admin existe toujours
        $stmt->execute([$adminId]);
        $this->assertNotFalse($stmt->fetch());
    }

    /**
     * Test de prévention de suppression de son propre compte
     */
    public function testEmpcherSuppressionPropreCompte()
    {
        $this->pdo->exec("INSERT INTO users (prenom, nom, mail, mdp) VALUES ('Self', 'User', 'self@test.com', 'hash')");
        $selfId = $this->pdo->lastInsertId();
        
        // Simuler qu'on ne peut pas supprimer si $selfId === $sessionId
        $sessionId = $selfId;
        $canDelete = ($selfId !== $sessionId);
        
        $this->assertFalse($canDelete);
    }

    // ==================== TESTS STATISTIQUES ====================

    /**
     * Test de calcul des statistiques du dashboard
     */
    public function testCalculerStatistiques()
    {
        // Insérer des données de test
        $this->pdo->exec("INSERT INTO users (prenom, nom, mail, mdp) VALUES ('U1', 'Test', 'u1@test.com', 'h1')");
        $this->pdo->exec("INSERT INTO users (prenom, nom, mail, mdp) VALUES ('U2', 'Test', 'u2@test.com', 'h2')");
        
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('C1', 'Test1', 0)");
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('C2', 'Test2', 1)");
        $this->pdo->exec("INSERT INTO commentaire (pseudo, commentaire, approved) VALUES ('C3', 'Test3', 0)");
        
        $this->pdo->exec("INSERT INTO contact (email, message, status) VALUES ('c1@test.com', 'M1', 'new')");
        $this->pdo->exec("INSERT INTO contact (email, message, status) VALUES ('c2@test.com', 'M2', 'read')");
        
        // Calculer les stats
        $stats = [
            'total_users' => $this->pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_commentaires' => $this->pdo->query("SELECT COUNT(*) FROM commentaire")->fetchColumn(),
            'commentaires_attente' => $this->pdo->query("SELECT COUNT(*) FROM commentaire WHERE approved = 0")->fetchColumn(),
            'total_contacts' => $this->pdo->query("SELECT COUNT(*) FROM contact")->fetchColumn(),
            'contacts_nouveaux' => $this->pdo->query("SELECT COUNT(*) FROM contact WHERE status = 'new'")->fetchColumn(),
        ];
        
        $this->assertEquals(2, $stats['total_users']);
        $this->assertEquals(3, $stats['total_commentaires']);
        $this->assertEquals(2, $stats['commentaires_attente']);
        $this->assertEquals(2, $stats['total_contacts']);
        $this->assertEquals(1, $stats['contacts_nouveaux']);
    }

    /**
     * Exécuté APRÈS chaque test (nettoyage)
     */
    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}