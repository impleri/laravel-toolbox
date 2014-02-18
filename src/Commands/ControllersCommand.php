<?php namespace Impleri\Toolbox\Commands;

class ControllersCommand extends BaseCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'toolbox:controllers';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate packaged controller files.';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->trump('toolbox.controllers', 'controllers');
    }
}
