<?php
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires simulant le comportement de connexion.php
 * - Inscription (hash du mot de passe + insertion)
 * - Empêchement d'inscription en double email
 * - Connexion réussie (password_verify)
 * - Tentatives échouées + blocage après N tentatives (simulation du mécanisme session)
 */

class ConnexionTest extends TestCase
{
    private ?PDO $pdo = null;

    protected function setUp(): void
    {
        // SQLite mémoire pour isolation complète
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la table users (compatible MySQL version simplifiée pour SQLite)
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                civilite VARCHAR(3),
                prenom VARCHAR(50) NOT NULL,
                nom VARCHAR(50) NOT NULL,
                mail VARCHAR(100) UNIQUE NOT NULL,
                mdp VARCHAR(255) NOT NULL,
                role VARCHAR(20),
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    // Helper : simule l'inscription
    private function registerUser(string $civilite, string $prenom, string $nom, string $email, string $password): bool
    {
        // Vérifier existant
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE mail = :mail");
        $stmt->execute([':mail' => $email]);
        if ($stmt->fetch()) {
            return false; // déjà existant
        }

        $mdp_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (civilite, prenom, nom, mail, mdp, role) 
                VALUES (:civilite, :prenom, :nom, :mail, :mdp, 'user')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':civilite' => $civilite,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':mail' => $email,
            ':mdp' => $mdp_hash
        ]);
    }

    // Helper : simule la logique de tentative de connexion (retourne array avec 'success' et 'message')
    // $attempts est un tableau référençable de timestamps des tentatives récentes (simulateur de $_SESSION['login_attempts'])
    private function attemptLogin(string $email, string $password, array &$attempts, int $maxAttempts = 5, int $blockDelay = 900): array
    {
        // Nettoyer anciennes tentatives
        $now = time();
        $attempts = array_filter($attempts, function($t) use ($now, $blockDelay) {
            return ($now - $t) < $blockDelay;
        });

        if (count($attempts) >= $maxAttempts) {
            $temps_restant = ceil(($blockDelay - ($now - min($attempts))) / 60);
            return ['success' => false, 'message' => "Trop de tentatives. Réessayer dans {$temps_restant} minutes."];
        }

        // Récupérer utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE mail = :mail LIMIT 1");
        $stmt->execute([':mail' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mdp'])) {
            // succès -> réinitialiser tentatives
            $attempts = [];
            return ['success' => true, 'message' => "Connexion réussie", 'user' => $user];
        } else {
            // Échec : ajouter timestamp aux tentatives
            $attempts[] = $now;
            $restantes = $maxAttempts - count($attempts);
            if ($restantes > 0) {
                return ['success' => false, 'message' => "Email ou mot de passe incorrect. Il vous reste {$restantes} tentative(s)."];
            } else {
                return ['success' => false, 'message' => "Trop de tentatives. Compte bloqué pendant " . ($blockDelay/60) . " minutes."];
            }
        }
    }

    public function testSuccessfulRegistration()
    {
        $ok = $this->registerUser('M.', 'Thierry', 'Decramp', 'thierry@example.test', 'StrongP@ssw0rd');
        $this->assertTrue($ok, "L'inscription devrait réussir");

        // Vérifier en base
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE mail = :mail");
        $stmt->execute([':mail' => 'thierry@example.test']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($row);
        $this->assertEquals('Thierry', $row['prenom']);
        // le mdp doit être hashé, donc différent du mot de passe en clair
        $this->assertNotEquals('StrongP@ssw0rd', $row['mdp']);
        $this->assertTrue(password_verify('StrongP@ssw0rd', $row['mdp']));
    }

    public function testDuplicateRegistrationIsPrevented()
    {
        $ok1 = $this->registerUser('M.', 'Jean', 'Dupont', 'jean@example.test', 'abc12345');
        $this->assertTrue($ok1);

        $ok2 = $this->registerUser('M.', 'Jean2', 'Dupont2', 'jean@example.test', 'differentPwd');
        $this->assertFalse($ok2, "L'inscription avec un email existant doit être refusée");
    }

    public function testSuccessfulLogin()
    {
        // créer utilisateur
        $this->registerUser('M.', 'Luc', 'Martin', 'luc@example.test', 'monMotDePasse');

        $attempts = [];
        $res = $this->attemptLogin('luc@example.test', 'monMotDePasse', $attempts);
        $this->assertTrue($res['success'], "La connexion devrait réussir avec le bon mot de passe");
        $this->assertEmpty($attempts, "Les tentatives doivent être réinitialisées après connexion réussie");
    }

    public function testFailedLoginAttemptsAndLockout()
    {
        // créer utilisateur
        $this->registerUser('M.', 'Marc', 'Leroy', 'marc@example.test', 'pwdCorrect');

        $attempts = [];
        // 1..(max-1) tentatives échouées
        for ($i = 1; $i <= 4; $i++) {
            $res = $this->attemptLogin('marc@example.test', 'badPassword', $attempts, 5, 900);
            $this->assertFalse($res['success']);
            $this->assertStringContainsString('Il vous reste', $res['message']);
        }

        // 5ème tentative -> verrouillage
        $res5 = $this->attemptLogin('marc@example.test', 'badPassword', $attempts, 5, 900);
        $this->assertFalse($res5['success']);
        $this->assertStringContainsString('Trop de tentatives', $res5['message']);

        // Tant que le délai n'est pas passé, la tentative suivante doit renvoyer blocage
        $res_after = $this->attemptLogin('marc@example.test', 'pwdCorrect', $attempts, 5, 900);
        $this->assertFalse($res_after['success']);
        $this->assertStringContainsString('Trop de tentatives', $res_after['message']);
    }

    public function testLoginWithNonExistingEmail()
    {
        $attempts = [];
        $res = $this->attemptLogin('noone@nowhere.test', 'whatever', $attempts);
        $this->assertFalse($res['success']);
        $this->assertStringContainsString('Email ou mot de passe incorrect', $res['message']);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}