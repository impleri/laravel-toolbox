<?php namespace Impleri\Toolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class BuildCommand extends Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'toolbox:build';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Executes all toolbox builders.';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->line('');

        $message = 'This will execute indiscriminately all methods which '
            . 'subscribe to the `toolbox.build` event.';
        $this->comment($message);
        $this->line('');
        Event::fire('toolbox.build', [$this]);

        // Done!
        $this->line('');
        $this->info('Compilation completed!');
        $this->line('');
    }
}
