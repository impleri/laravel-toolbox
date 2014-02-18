<?php namespace Impleri\Toolbox\Commands;

class SchemaCommand extends BaseCommand
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
        $this->trump('toolbox.schema', 'migration schema');
    }
}
