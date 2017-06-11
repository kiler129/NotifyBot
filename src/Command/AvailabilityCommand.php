<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Command;

use noFlash\NotifyBot\Configuration\ConfigurationProvider;
use noFlash\NotifyBot\ProductChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AvailabilityCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var StyleInterface
     */
    private $io;

    /**
     * @var ConfigurationProvider
     */
    private $configurationProvider;

    public function __construct(ConfigurationProvider $configurationProvider)
    {
        $this->configurationProvider = $configurationProvider;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('notify:availability')->setDescription('Check availability');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);


        $productChecker = ProductChecker::createFromConfiguration(
            $this->configurationProvider->getProduct(),
            $this->configurationProvider->getNotification()
        );


        $availability = iterator_to_array($productChecker->getAvailability());
        usort(
            $availability,
            function ($a, $b) {
                return $a['when'] <=> $b['when'];
            }
        );

        $rows = [];
        foreach ($availability as $row) {
            /** @var \DateTimeInterface $when */
            $when = $row['when'];

            $wait = ($row['waitDays'] > 0) ?
                sprintf('%d day(s)', $row['waitDays']) : 'Pickup today!';

            $rows[] = [
                $row['where'],
                $when->format('Y/m/d'),
                $wait
            ];
        }

        $this->io->title('Availability details');
        $this->io->table(['Where', 'When', 'Wait'], $rows);
    }
}
