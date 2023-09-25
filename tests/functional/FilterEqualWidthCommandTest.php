<?php

declare(strict_types=1);

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FilterEqualWidthCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:filter:equal-width');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteSuccess(): void
    {
        $this->commandTester->execute([
            '--values' => '0.1,3.4,3.5,3.6,7.0,9.0,6.0,4.4,2.5,3.9,4.5,2.8',
        ]);
        $this->commandTester->assertCommandIsSuccessful();

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Low:    0.1, 3.4, 3.5, 3.6, 2.5, 3.9, 2.8', $output);
        $this->assertStringContainsString('Medium: 7, 6, 4.4, 4.5', $output);
        $this->assertStringContainsString('High:   9', $output);
    }
}
