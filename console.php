<?php 
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Scottpringle\Console\Command\CopyspecialdaysCommand;
use Scottpringle\Console\Command\RevertCommand;

$console = new Application();
$console->add(new CopyspecialdaysCommand());
$console->add(new RevertCommand());

$console->run();

?>
