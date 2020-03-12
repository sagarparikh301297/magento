<?php


namespace SimpleMagento\Tools\Console\Command;

use Symfony\Component\Console\Command\Command; // for parent class
use Symfony\Component\Console\Input\InputInterface; // for InputInterface used in execute method
use Symfony\Component\Console\Output\OutputInterface; // for OutputInterface used in execute method
use Symfony\Component\Filesystem\Filesystem;

class Generation extends Command
{
    protected function configure()
    {
        $this->setName('clear:generation')
            ->setDescription('Clear generated folder!');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        try {
            if($fs->exists('generated')){
                $fs->remove(array('generated'));
                $output->writeln('Cleared generation!');
            }
            else {
                $output->writeln('Can\'t find directory');
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while deleting your directory at ".$e->getPath();
        }
    }
}