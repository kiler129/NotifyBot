<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Command;

use noFlash\NotifyBot\Configuration\ConfigurationProvider;
use noFlash\NotifyBot\Mail\Mailer;
use noFlash\NotifyBot\ProductChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class NotifyCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var ConfigurationProvider
     */
    private $configurationProvider;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var int
     */
    private $lastNotification = -1;

    public function __construct(
        ConfigurationProvider $configurationProvider,
        Mailer $mailer)
    {
        $this->configurationProvider = $configurationProvider;

        parent::__construct();
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('notify:run')->setDescription('Notification daemon')->setHelp(
                'Runs notification daemon'
            );
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

        while (true) {
            try {
                if ($this->performCheck($productChecker)) {
                    $this->sendNotification($productChecker);
                }
            } catch (\Throwable $t) {
                $this->io->error('Checking failed: ' . $t->getMessage());

                if ($output->isDebug()) {
                    throw $t;
                }
            }

            sleep(5); //Not bad I think ;)
        }
    }

    private function performCheck(ProductChecker $productChecker): bool
    {
        $isToday = $productChecker->isAvailableToday();

        $msg = ($isToday) ? 'As of %s at least one store has it today :)' : 'As of %s no stores has it today :(';
        $this->io->writeln(sprintf($msg, date('Y/m/d H:i:s')));

        return $isToday;
    }

    private function sendNotification(ProductChecker $productChecker): void
    {
        if (time() < $this->lastNotification +
                     $this->configurationProvider->getNotification()->getGrace()) {
            return; //Do not spam me!
        }

        $this->mailer->sendTextMessage(
            $this->configurationProvider->getNotification()->getTo(),
            'Product availability',
            sprintf(
                'Product %s is available for pickup TODAY at %s - go and grab one!',
                $productChecker->getProductCode(),
                $productChecker->whereAvailableToday()
            )
        );

        //todo some results checking
        $this->lastNotification = time();
    }
}
