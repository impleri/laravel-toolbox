<?php namespace Impleri\Toolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class ModelsCommand extends Command
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
        $this->line('');

        $message = 'This will generate models for all of the packages that'
            . ' subscribe to the `toolbox.models` event.';
        $this->comment($message);
        $this->line('');

        if ($this->confirm('Proceed with the rebuild? [Yes|no]')) {
            $this->line('');

            // Trigger generation of models
            $this->info('Generating models...');
            $routes = Event::fire('toolbox.models');

            // Done!
            $this->line('');
            $this->info('Process completed!');
            $this->line('');
        }
    }
}
