<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TriBucketFilterEqualFrequency;
use App\Service\TriBucketFilterEqualWidth;
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
#[AsCommand(name: 'app:filter')]
class FilterCommand extends Command implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    private const SUPPORTED_FILTERS = [
        'equal-frequency',
        'equal-width',
    ];

    protected function configure(): void
    {
        $this
            ->addOption('method', null, InputOption::VALUE_REQUIRED, 'The filter method to use, which must be one of '.implode(', ', self::SUPPORTED_FILTERS))
            ->addOption('values', null, InputOption::VALUE_REQUIRED, 'The comma separated list of values to be filtered')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $method = (string) $input->getOption('method');
        if (!in_array($method, self::SUPPORTED_FILTERS)) {
            throw new \InvalidArgumentException(sprintf('Unsupported filter method "%s", must be one of %s', $method, implode(', ', self::SUPPORTED_FILTERS)));
        }
        $values = (string) $input->getOption('values');

        switch ($method) {
            case 'equal-frequency':
                $filtered = $this->getFilterFrequency()->filter(explode(',', $values));
                break;
            case 'equal-width':
                $filtered = $this->getFilterWidth()->filter(explode(',', $values));
                break;
            default:
                throw new \LogicException('Failed to handle all possible filter methods');
        }

        /** @psalm-suppress MixedArgument */
        $output->writeln([
            'Low:    '.implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_LOW]),
            'Medium: '.implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_MEDIUM]),
            'High:   '.implode(', ', $filtered[TriBucketFilterEqualFrequency::BUCKET_HIGH]),
        ]);

        return self::SUCCESS;
    }

    /** @psalm-suppress MixedInferredReturnType */
    #[SubscribedService]
    private function getFilterFrequency(): TriBucketFilterEqualFrequency
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->container->get(__METHOD__);
    }

    /** @psalm-suppress MixedInferredReturnType */
    #[SubscribedService]
    private function getFilterWidth(): TriBucketFilterEqualWidth
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->container->get(__METHOD__);
    }
}
