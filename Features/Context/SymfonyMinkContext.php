<?php

namespace Elcweb\CommonBundle\Features\Context;

use Behat\Symfony2Extension\Driver\KernelDriver;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\CommonContexts\MinkRedirectContext;
use Behat\CommonContexts\SymfonyMailerContext;
use Behat\CommonContexts\SymfonyDoctrineContext;
use Behat\Mink\Exception\UnsupportedDriverActionException,
    Behat\Mink\Exception\ExpectationException;
use Behat\MinkBundle\Driver\SymfonyDriver;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
abstract class SymfonyMinkContext extends MinkContext implements KernelAwareInterface
{
    protected $kernel;
    protected $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('mink_redirect', new MinkRedirectContext());
        $this->useContext('symfony_extra', new SymfonyMailerContext());
        $this->useContext('symfony_doctrine_context', new SymfonyDoctrineContext);
    }

    /**
     * Clean database before scenario starts
     *
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        // Asks subcontext SymfonyDoctrineContext to rebuild database schema
        $this
            ->getMainContext()
            ->getSubcontext('symfony_doctrine_context')
            ->buildSchema($event);
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    /**
     * @return Behat\Mink\Element\DocumentElement
     */
    protected function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then /^break$/
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {
        }
        fwrite(STDOUT, "\033[u");

        return;
    }

    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();

        if (!$driver instanceof KernelDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with ' .
                '"@mink:symfony". Using the profiler is not ' .
                'supported by %s', $driver
            );
        }

        $profile = $driver->getClient()->getProfile();

        if (false === $profile) {
            throw new \RuntimeException(
                'Emails cannot be tested as the profiler is ' .
                'disabled.'
            );
        }

        return $profile;
    }

    public function canIntercept()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof KernelDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with ' .
                '"@mink:goutte" or "@mink:symfony". ' .
                'Intercepting the redirections is not ' .
                'supported by %s', $driver
            );
        }
    }

    /**
     * @Given /^I should get an email on "([^"]*)" with "([^"]*)"$/
     */
    public function iShouldGetAnEmail($email, $text)
    {
        $error     = sprintf('No message sent to "%s"', $email);
        $profile   = $this->getSymfonyProfile();

        $collector = $profile->getCollector('swiftmailer');

        foreach ($collector->getMessages() as $message) {
            // Checking the recipient email and the X-Swift-To
            // header to handle the RedirectingPlugin.
            // If the recipient is not the expected one, check
            // the next mail.
            $correctRecipient = array_key_exists(
                $email, $message->getTo()
            );
            $headers          = $message->getHeaders();
            $correctXToHeader = false;
            if ($headers->has('X-Swift-To')) {
                $correctXToHeader = array_key_exists($email,
                    $headers->get('X-Swift-To')->getFieldBodyModel()
                );
            }

            if (!$correctRecipient && !$correctXToHeader) {
                continue;
            }

            try {
                // checking the content
                return assertContains(
                    $text, $message->getBody()
                );
            } catch (AssertException $e) {
                $error = sprintf(
                    'An email has been found for "%s" but without ' .
                    'the text "%s".', $email, $text
                );
            }
        }

        throw new ExpectationException($error, $this->getSession());
    }
}
