<?php namespace Impleri\Toolbox\Commands;

class ViewsCommand extends BaseCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'toolbox:views';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Clone packaged views.';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->trump('toolbox.views', 'views');
    }
}
