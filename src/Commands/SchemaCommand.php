<?php namespace Impleri\Toolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class SchemaCommand extends Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'toolbox:schema';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate packaged schema migrations.';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->line('');

        $message = 'This will generate schema for all of the packages that'
            . ' subscribe to the `toolbox.schema` event.';
        $this->comment($message);
        $this->line('');

        if ($this->confirm('Proceed with the rebuild? [Yes|no]')) {
            $this->line('');

            // Trigger generation of models
            $this->info('Generating migrations...');
            $routes = Event::fire('toolbox.schema');

            // Done!
            $this->line('');
            $this->info('Process completed!');
            $this->line('');
        }
    }
}
