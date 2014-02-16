<?php

use \Mockery;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;

class ToolboxCommandsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Build Command Test
     *
     * Ensure BuildCommand fires correctly.
     */
    public function testBuildIsCalled()
    {
        $this->runEventWithCommand('toolbox.build', 'BuildCommand');
    }

    /**
     * Routes Command Test
     *
     * Ensure RoutesCommand fires correctly.
     */
    public function testRoutesIsCalled()
    {
        File::shouldReceive('exists')->with('app/routes.bak.php')->andReturn(false);
        File::shouldReceive('exists')->with('app/routes.php')->andReturn(false);
        File::shouldReceive('put')->with('app/routes.php');
        $this->runEvent('toolbox.routes', 'RoutesCommand');
    }

    /**
     * Controllers Command Test
     *
     * Ensure ControllersCommand fires correctly.
     */
    public function testControllersIsCalled()
    {
        $this->runEvent('toolbox.controllers', 'ControllersCommand');
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

        $helpers = new HelperSet(
            [
                'dialog' => $dialog
            ]
        );
        $command->setHelperSet($helpers);

        return $command;
    }

    /**
     * Run Event With Command
     *
     * Common method to set Event facade to listen for an event with a parameter
     * before triggering an Artisan command.
     * @param  string $event       Name of Event to listen for
     * @param  string $commandName Class name of the command to instantiate
     */
    public function runEventWithCommand($event, $commandName)
    {
        $command = $this->getCommand($commandName);
        Event::shouldReceive('fire')->once()->with($event, [$command]);
        $this->runCommand($command);
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
        Event::shouldReceive('fire')->once()->with($event);
        $this->runCommand($command);
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
     * Tear Down
     *
     * Clean up any mocked objects we created.
     */
    public function tearDown()
    {
        Mockery::close();
    }
}
