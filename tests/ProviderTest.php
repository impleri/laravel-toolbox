<?php

use \Mockery;
use Codeception\Specify;
use Codeception\Verify;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

class ProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Consume Codeception's Specify Trait
     */
    use Specify;

    public function getProvider($mocked = false)
    {
        $class = 'Impleri\Toolbox\ToolboxServiceProvider';
        if ($mocked) {
            $class .= '[' . implode(',', $mocked) . ']';
        }
        return ($mocked) ? Mockery::mock($class, [app()]) : new $class(app());
    }

    /**
     * Do the Artisan commands fire?
     */
    public function testCommands()
    {
        $self = $this;

        $this->prepareSpecify();
        $this->specify(
            'Boots',
            function () use ($self) {
                $target = $self->getProvider(['package']);
                $target->shouldReceive('package');
                $target->boot();
            }
        );

        $this->prepareSpecify();
        $this->specify(
            'Identifies provisions',
            function () use ($self) {
                $target = $self->getProvider();
                verify($target->provides())->notEmpty();
            }
        );

        $this->prepareSpecify();
        $this->specify(
            'Binds to application',
            function () use ($self) {
                App::shouldReceive('bind')->with('/^toolbox\.commands\./', Mockery::on(function ($closure) {
                    $command = $closure();
                    verify_that('is a command', is_a($command, 'Illuminate\Console\Command'));
                    return true;
                }));
                Event::shouldReceive('listen')->with('toolbox.build', Mockery::on(function ($closure) {
                    $app = Mockery::mock('Illuminate\Console\Application[call]');
                    $app->shouldReceive('call');
                    $command = $closure($app);
                    return true;
                }));
                $target = $self->getProvider(['commands']);
                $target->shouldReceive('commands')->with(Mockery::type('array'));
                $target->register();
            }
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
