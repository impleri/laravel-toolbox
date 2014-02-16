<?php namespace Impleri\Toolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class ControllersCommand extends Command
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
        $this->line('');

        $message = 'This will generate controllers for all of the packages that'
            . ' subscribe to the `toolbox.controllers` event.';
        $this->comment($message);
        $this->line('');

        if ($this->confirm('Proceed with the rebuild? [Yes|no]')) {
            $this->line('');

            // Trigger generation of controllers
            $this->info('Generating controllers...');
            $routes = Event::fire('toolbox.controllers');

            // Done!
            $this->line('');
            $this->info('Process completed!');
            $this->line('');
        }
    }
}
