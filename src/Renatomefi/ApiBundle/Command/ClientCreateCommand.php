<?php

namespace Renatomefi\ApiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClientCreateCommand
 * @package Renatomefi\ApiBundle\Command
 */
class ClientCreateCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('renatomefi:oauth-server:client:create')
            ->setDescription('Create a new client')
            ->addArgument('name', InputArgument::REQUIRED, 'Sets the client name', null)
            ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.', null)
            ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types.', null);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getApplication()->getKernel()->getContainer()->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->createClient();
        $client->setName($input->getArgument('name'));
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $clientManager->updateClient($client);
        $output->writeln(
            sprintf(
                'Added a new client with name <info>%s</info>, public id <info>%s</info> and secret <info>%s</info>.',
                $client->getName(),
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }
}