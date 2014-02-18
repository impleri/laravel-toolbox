<?php namespace Impleri\Toolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

abstract class BaseCommand extends Command
{
    /**
     * Execute the console command.
     */
    protected function trump($event, $label)
    {
        $this->line('');

        $message = 'This will generate ' . $label . ' for all of the packages that'
            . ' subscribe to the `' . $event . '` event.';
        $this->comment($message);
        $this->line('');

        if ($this->confirm('Proceed with rebuilding ' . $label . '? [Yes|no]')) {
            $this->line('');

            // Trigger generation
            $this->info('Generating ' . $label . '...');
            $routes = Event::fire($event);

            // Done!
            $this->line('');
            $this->info('Process completed!');
            $this->line('');
        }
    }
}
