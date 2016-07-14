<?php

namespace Scottpringle\Console\Command;

use Scottpringle\Console\Model\Db;
use Scottpringle\Console\Model\Vendor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CopyspecialdaysCommand
 * @package Scottpringle\Console\Command
 */
class RevertCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('specialdays:revert')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // first script
        // create db dump or copy db table to temp table
        $db = new Db();

        // create a backup just in case there is a problem
        $db->dump("backups/before-revert".time().".sql");

        $db->dropTable("vendor_schedule");
        $db->renameTable("vendor_schedule_tmp", "vendor_schedule");

        $output->writeln('<info>Renamed original table</info>');
    }
}

