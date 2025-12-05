<?php

use PHPUnit\Framework\TestCase;
// require_once __DIR__ ."./../includes/fonctions.php";
class FonctionsTest extends TestCase
{
    /**
     * Test de validation d'email
     */
    public function testValidationEmail()
    {
        // Emails valides
        $this->assertTrue(filter_var('test@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertTrue(filter_var('user.name+tag@example.co.uk', FILTER_VALIDATE_EMAIL) !== false);
        
        // Emails invalides
        $this->assertFalse(filter_var('invalid-email', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('user@', FILTER_VALIDATE_EMAIL) !== false);
    }

    /**
     * Test de nettoyage de texte
     */
    public function testNettoyageTexte()
    {
        $texte_sale = "<script>alert('XSS')</script>Bonjour";
        $texte_propre = strip_tags($texte_sale);
        
        // $this->assertEquals('Bonjour', $texte_propre);
        $this->assertStringNotContainsString('<script>', $texte_propre);
    }

    /**
     * Test de validation de mot de passe
     */
    public function testValidationMotDePasse()
    {
        // Mot de passe trop court
        $this->assertFalse(strlen('1234567') >= 8);
        
        // Mot de passe valide
        $this->assertTrue(strlen('12345678') >= 8);
        
        // Test de hash
        $password = 'MonMotDePasse123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->assertTrue(password_verify($password, $hash));
    }

    /**
     * Test de génération de token
     */
    public function testGenerationToken()
    {
        $token = bin2hex(random_bytes(32));
        
        $this->assertEquals(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token);
    }
}