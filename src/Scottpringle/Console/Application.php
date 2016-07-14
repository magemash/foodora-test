<?php 
namespace Scottpringle\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package Scottpringle\Console
 */
class Application extends BaseApplication
{
    const NAME = 'Scottpringle\' Console Application';
    const VERSION = '1.0';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);
    }
}
?>
