<?php

/*
 * This file is part of the Curler7UserBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Command;

use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'curler7:user:create';

    public function __construct(private UserManagerInterface $userManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('curler7:user:create')
            ->setDescription('Create a user.')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
                new InputOption('verified', null, InputOption::VALUE_NONE, 'Set the user as verified'),
            ])
            ->setHelp(<<<'EOT'
The <info>curler7:user:create</info> command creates a user:

  <info>php %command.full_name% neo</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php %command.full_name% neo neo@example.com mypassword</info>

You can create a super admin via the super-admin flag:

  <info>php %command.full_name% neo --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% neo --inactive</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username   = $input->getArgument('username');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $inactive   = $input->getOption('inactive');
        $superAdmin = $input->getOption('super-admin');
        $verified = $input->getOption('verified');

        $user = $this->userManager->createUser()->setUsername($username)
            ->setPlainPassword($password)
            ->setEmail($email)
            ->setEnabled(!$inactive)
            ->setVerified($verified)
        ;

        if ($superAdmin) {
            $user->addRole(UserInterface::ROLE_SUPER_ADMIN);
        }

        $this->userManager->updateUser($user);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
