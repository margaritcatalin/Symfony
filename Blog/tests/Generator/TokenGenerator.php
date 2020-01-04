<?php
namespace App\Tests\Security;
use App\Security\TokenGenerator;
use Symfony\Bundle\WebProfilerBundle\Tests\TestCase;
class TokenGeneratorTest extends TestCase
{
    public function testTokenGenerator()
    {
        $tokenGen = new TokenGenerator();
        $token = $tokenGen->getRandomSecureToken(30);
        $this->assertEquals(30, strlen($token));
        $this->assertTrue(ctype_alnum($token), 'This test contains incorrect character');
    }
}