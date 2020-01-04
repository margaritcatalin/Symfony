<?php
namespace App\Tests\Mailer;
use App\Entity\User;
use App\Mailer\Mailer;
use Symfony\Bundle\WebProfilerBundle\Tests\TestCase;
class MailerTest extends TestCase
{
    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('margarit.catalin97@gmail.com');
        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $swiftMailer->expects($this->once())->method('send')
            ->with($this->callback(function ($subject){
                $messageStr = (string)$subject;
                dump($messageStr);
                return  strpos($messageStr, 'From: test@blog.ro') !== false 
                        && strpos($messageStr, 'Content-Type: text/html; charset=utf-8') !== false 
                        && strpos($messageStr, 'Subject: Blog Registration') !== false
                        && strpos($messageStr, 'To: margarit.catalin97@gmail.com') !== false
                        && strpos($messageStr, 'This is a message body') !== false;
            }));
        $twigMock = $this->getMockBuilder(\Twig\Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->expects($this->once())->method('render')
            ->with('email/registration.html.twig', 
                [
                    'user' =>$user
                ]
            )->willReturn('This is a message body');
        $mailer = new Mailer($swiftMailer, $twigMock, 'test@blog.ro');
        $mailer->sendConfirmationEmail($user);
    }
}