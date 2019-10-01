<?php


namespace App\Commands;


use App\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected function configure()
    {
        $this->addArgument('username',InputArgument::REQUIRED, 'The username');
        $this->addArgument('password',InputArgument::OPTIONAL, 'The username');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating user.');

        $user = new User();
        $user->username = $input->getArgument('username');
        $user->password = $input->getArgument('password') ?? $user->username;
        $user->name = 'User';
        $user->lastname = 'User';
        $user->email = $user->username.'@mail.com';
        $user->phone = 44444444;
        $user->summary = '';

        $user->save();

        $output->writeln('Done.');
    }
}