<?php
/**
 * Created by PhpStorm.
 * Date: 21/09/2017
 * Time: 13:32
 */
namespace AppBundle\Command;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallAllCommand extends ContainerAwareCommand
{
    /**
     * Configure de la commande
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('torrent:call_all_command')
            ->setDescription('Exec de toutes les commandes');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getContainer()->get('kernel');

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $aCommand = array(
            'torrent:push_torrent',
            'torrent:show_trans',
            'torrent:wget_file',
            'torrent:read_log_wget_file'
        );

        foreach ($aCommand as $sCommand) {
            $input = new ArrayInput(['command' => $sCommand]);
            $application->run($input);
        }
    }
}
