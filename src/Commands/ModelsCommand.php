<?php namespace Impleri\Toolbox\Commands;

class ModelsCommand extends BaseCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'toolbox:models';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate packaged model files.';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->trump('toolbox.models', 'models');
    }
}
