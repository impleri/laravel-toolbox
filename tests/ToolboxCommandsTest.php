<?php

use \Mockery;
use Codeception\Specify;
use Codeception\Verify;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class ToolboxCommandsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Consume Codeception's Specify Trait
     */
    use Specify;

    /**
     * Do the Artisan commands fire?
     */
    public function testCommands()
    {
        $self = $this;

        $this->prepareSpecify();
        $this->specify('Build executes', function () use ($self) {
                $command = $self->getCommand('BuildCommand');
                Event::shouldReceive('fire')->once()->with('toolbox.build', [$command]);
                $self->runCommand($command);
            }
        );

        $this->prepareSpecify();
        $this->specify('Controllers executes', function () use ($self) {
                $self->runEvent('toolbox.controllers', 'ControllersCommand');
            }
        );

        $this->prepareSpecify();
        $this->specify('Models executes', function () use ($self) {
                $self->runEvent('toolbox.models', 'ModelsCommand');
            }
        );

        $this->prepareSpecify();
        $this->specify('Routes executes without existing files', function () use ($self) {
                File::shouldReceive('exists')
                    ->with(Mockery::anyOf('app/routes.php', 'app/routes.bak.php'))
                    ->andReturn(false);
                File::shouldReceive('put')
                    ->with('app/routes.php', Mockery::type('string'));

                $self->runEvent('toolbox.routes', 'RoutesCommand');
            }
        );

        $this->prepareSpecify();
        $this->specify('Routes executes with existing files', function () use ($self) {
                File::shouldReceive('exists')
                    ->with(Mockery::anyOf('app/routes.php', 'app/routes.bak.php'))
                    ->andReturn(true);
                File::shouldReceive('delete')
                    ->with('app/routes.bak.php');
                File::shouldReceive('move')
                    ->with('app/routes.php', 'app/routes.bak.php');
                File::shouldReceive('put')
                    ->with('app/routes.php', Mockery::type('string'));

                $command = $self->getCommand('RoutesCommand');
                Event::shouldReceive('fire')->once()->with('toolbox.routes')->andReturn([]);
                $self->runCommand($command);
            }
        );

        $this->prepareSpecify();
        $this->specify('BuildCommand executes', function () use ($self) {
                $self->runEvent('toolbox.schema', 'SchemaCommand');
            }
        );

        $this->prepareSpecify();
        $this->specify('BuildCommand executes', function () use ($self) {
                $self->runEvent('toolbox.views', 'ViewsCommand');
            }
        );
    }

    /**
     * Run Event
     *
     * Common method to set Event facade to listen for an event before triggering
     * an Artisan command.
     * @param  string $event       Name of Event to listen for
     * @param  string $commandName Class name of the command to instantiate
     */
    public function runEvent($event, $commandName)
    {
        $command = $this->getCommand($commandName);
        Event::shouldReceive('fire')->once()->with($event)->andReturn([1]);
        $this->runCommand($command);
    }

    /**
     * Get Command
     *
     * Internal method to generate an Artisan command prepared with a mocked
     * dialog.
     * @param  string $commandName Class name of the command to instantiate
     * @return Illuminate\Console\Command Artisan command
     */
    public function getCommand($commandName)
    {
        $commandName = 'Impleri\Toolbox\Commands\\' . $commandName;
        $command = new $commandName;
        $dialog = Mockery::mock('Symfony\Component\Console\Helper\DialogHelper[askConfirmation]');
        $dialog->shouldReceive('askConfirmation')->andReturn(true);

        $helpers = new HelperSet(['dialog' => $dialog]);
        $command->setHelperSet($helpers);

        return $command;
    }

    /**
     * Run Command
     *
     * Common method to execute an Artisan command.
     * @param  Illuminate\Console\Command $command Artisan command
     * @return integer
     */
    public function runCommand($command)
    {
        return $command->run(
            new Symfony\Component\Console\Input\ArrayInput([]),
            new Symfony\Component\Console\Output\NullOutput
        );
    }

    /**
     * Set up for each spec
     */
    protected function prepareSpecify()
    {
        $this->cleanSpecify();
        $this->afterSpecify(function () {
            Mockery::close();
        });
    }
}
