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
class CopyspecialdaysCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('specialdays:copy')
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
        $db->dump("backups/before-copyspecial".time().".sql");

        // rename the table and then copy the table structure for a new table
        $db->renameTable("vendor_schedule", "vendor_schedule_tmp");
        $db->cloneTable("vendor_schedule_tmp", "vendor_schedule");

        // create tmp table
        $vendorModel = new Vendor();
        $allVendors = $vendorModel->fetchAll();

        // loop through the vendors, creating a new vendor object for each
        foreach ($allVendors as $v) {
            $vendor = new Vendor();
            $vendor->findOne($v['id']);

            $vendor->convertAllSpecialToNormal();
            $vendor->saveNewDays();
        }

        $output->writeln('<info>Updated Vendor schedule days</info>');
    }
}

