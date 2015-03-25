<?php

/*
 * This file is part of sammui project.
 *
 * For the full copyright and license information, please
 * view the LICENSE file that was distributed with this
 * source code.
 *
 * Este arquivo faz parte do projeto sammui.
 *
 * Para acesso completo à licença e copyright, acesse o
 * arquivo LICENSE na raiz do projeto.
 *
 * (c) PensandooDireito SAL/MJ <https://github.com/pensandoodireito>
 * (c) Renato Mendes Figueiredo <renato@renatomefi.com.br>
 */

namespace Command;

use OAuth2\OAuth2;
use Renatomefi\ApiBundle\Command\ClientCreateCommand;
use Renatomefi\ApiBundle\DataFixtures\MongoDB\LoadOAuthClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ClientCreateCommandTest
 * @package Command
 */
class ClientCreateCommandTest extends KernelTestCase
{

    /**
     * @var
     */
    protected $clientName;

    public function setUp()
    {
        $this->clientName = LoadOAuthClient::CLIENT_NAME . '-' . ClientCreateCommand::$commandName;
    }

    /**
     * @return string
     */
    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new ClientCreateCommand());

        $command = $application->find(ClientCreateCommand::$commandName);
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'name' => $this->clientName,
            '--redirect-uri' => ['/'],
            '--grant-type' => [OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS]
        ]);

        $this->assertEquals(0, $commandTester->getStatusCode());

        return $commandTester->getDisplay();
    }

    /**
     * @depends testExecute
     * @param $display
     */
    public function testClientAndRemove($display)
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $clientManager = $kernel->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->findClientBy(['name' => $this->clientName]);

        $expectedDisplay = sprintf(
            'Added a new client with name %s, public id %s and secret %s.',
            $client->getName(),
            $client->getPublicId(),
            $client->getSecret()
        );

        $this->assertStringStartsWith($expectedDisplay, $display);

        //Deleting
        $dm = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $dm->remove($client);
        $dm->flush();
    }

}