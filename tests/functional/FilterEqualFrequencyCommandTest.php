<?php

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FilterEqualFrequencyCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:filter:equal-frequency');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteSuccess(): void
    {
        $this->commandTester->execute([
            '--values' => '0.1,3.4,3.5,3.6,7.0,9.0,6.0,4.4,2.5,3.9,4.5,2.8',
        ]);
        $this->commandTester->assertCommandIsSuccessful();

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Low:    0.1, 3.4, 2.5, 2.8', $output);
        $this->assertStringContainsString('Medium: 3.5, 3.6, 4.4, 3.9', $output);
        $this->assertStringContainsString('High:   7, 9, 6, 4.5', $output);
    }
}
