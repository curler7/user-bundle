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

namespace Curler7\UserBundle\Tests\Command;

use Curler7\UserBundle\Command\CreateUserCommand;
use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class CreateUserCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $commandTester = $this->createCommandTester($this->getUserManager(), new Application());
        $exitCode      = $commandTester->execute([
            'username' => 'user',
            'email'    => 'email',
            'password' => 'pass',
        ], [
            'decorated'   => false,
            'interactive' => false,
        ]);

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertMatchesRegularExpression('/Created user user/', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper(): void
    {
        $application = new Application();

        $helper = $this->getMockBuilder(QuestionHelper::class)
            ->onlyMethods(['ask'])
            ->getMock();

        $helper->expects($this->exactly(3))
            ->method('ask')
            ->willReturnOnConsecutiveCalls('user', 'email', 'pass');

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester($this->getUserManager(), $application);
        $exitCode = $commandTester->execute([], [
            'decorated'   => false,
            'interactive' => true,
        ]);

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertMatchesRegularExpression('/Created user user/', $commandTester->getDisplay());
    }

    private function createCommandTester(UserManagerInterface $userManager, Application $application): CommandTester
    {
        $application->setAutoExit(false);

        $application->add(new CreateUserCommand($userManager));

        return new CommandTester($application->find('curler7:user:create'));
    }

    private function getUserManager(
        string $username = 'user',
        string $password = 'pass',
        string $email = 'email',
        bool $active = true,
        bool $superAdmin = false
    ): UserManagerInterface
    {
        $manager = $this->getMockBuilder(UserManagerInterface::class)->getMock();

        $user = $this->getMockBuilder(UserInterface::class)->getMock();

        $user->expects($this->once())
            ->method('setUsername')
            ->with($username)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setPlainPassword')
            ->with($password)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setEmail')
            ->with($email)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setEnabled')
            ->with($active)
            ->willReturn($user);

        if ($superAdmin) {
            $user->expects($this->once())
                ->method('addRole')
                ->with('ROLE_SUPER_ADMIN')
                ->willReturn($user);
        }

        $manager
            ->expects($this->once())
            ->method('createUser')
            ->willReturn($user);

        $manager
            ->expects($this->once())
            ->method('updateUser')
            ->with($this->isInstanceOf(UserInterface::class));

        return $manager;
    }
}
