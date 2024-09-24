<?php

namespace NS\PurearthBundle\Command;

use NS\Purearth\User\Command\UpdateUserPasswordCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeUserPasswordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('purearth:user:reset-password')
            ->setDescription('Reset a User\'s password')
            ->setDefinition(array(
                new InputArgument(
                    'user',
                    InputArgument::REQUIRED,
                    'Email address'
                ),
                new InputArgument(
                    'password',
                    InputArgument::REQUIRED,
                    'New Password'
                ),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NS\Purearth\User\User')->findOneBy(['email' => $input->getArgument('user')]);
        if(!$user) {
            $output->writeln('User not found');
        }
        else
        {
            $command = new UpdateUserPasswordCommand($user->getId(), $input->getArgument('password'));
            $this->getContainer()->get('command_bus')->handle($command);

            $output->writeln('User '.$user->getEmail().' updated.');
        }
    }
}