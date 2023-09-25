<?php

namespace App\Command;

use App\Service\TriBucketFilterEqualFrequency;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[AsCommand(name: 'app:filter:equal-frequency')]
class FilterEqualFrequencyCommand extends Command implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    protected function configure(): void
    {
        $this
            ->addOption('values', null, InputOption::VALUE_REQUIRED, 'The comma separated list of values to be filtered')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $values = (string) $input->getOption('values');
        $filtered = $this->getFilter()->filter(explode(',', $values));

        /** @psalm-suppress MixedArgument */
        $output->writeln([
            'Low:    ' . implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_LOW]),
            'Medium: ' . implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_MEDIUM]),
            'High:   ' . implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_HIGH]),
        ]);

        return self::SUCCESS;
    }

    /** @psalm-suppress MixedInferredReturnType */
    #[SubscribedService]
    private function getFilter(): TriBucketFilterEqualFrequency
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->container->get(__METHOD__);
    }
}
