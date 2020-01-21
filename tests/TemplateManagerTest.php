<?php

use Context\ApplicationContext;
use Entity\Quote;
use Entity\Template;
use Entity\User;
use Faker\Factory;
use Manager\TemplateManager;
use PHPUnit\Framework\TestCase;
use Repository\DestinationRepository;

class TemplateManagerTest extends TestCase
{
    /**
     * Test with current user
     * @test
     */
    public function testWithCurrentUser()
    {
        $faker = Factory::create();

        $expectedDestination = DestinationRepository::getInstance()->getById($faker->randomNumber());
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $expectedDestination->getId(), $faker->dateTime());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");
        $templateManager = new TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $expectedUser->getFirstname() . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->getContent());
    }


    /**
     * Test with defined user
     * @test
     */
    public function testWithUser()
    {
        $faker = Factory::create();

        $expectedDestination = DestinationRepository::getInstance()->getById($faker->randomNumber());
        $expectedUser = new User($faker->randomNumber(), $faker->firstName, $faker->lastName, $faker->email);

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $expectedDestination->getId(), $faker->dateTime());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");
        $templateManager = new TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote,
                'user' => $expectedUser
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $expectedUser->getFirstname() . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->getContent());
    }

    /**
     * Test with defined user
     * @test
     */
    public function testWithQuoteSummary()
    {
        $faker = Factory::create();

        $expectedDestination = DestinationRepository::getInstance()->getById($faker->randomNumber());
        $expectedUser = new User($faker->randomNumber(), $faker->firstName, $faker->lastName, $faker->email);

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $expectedDestination->getId(), $faker->dateTime());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].
[quote:summary_html]

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");
        $templateManager = new TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote,
                'user' => $expectedUser
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $expectedUser->getFirstname() . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->getCountryName() . ".
<p>".$quote->getId()."</p>

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->getContent());
    }
}
