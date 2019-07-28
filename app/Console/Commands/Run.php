<?php

namespace App\Console\Commands;

use Dungeon\CommandRunner;
use Dungeon\Exceptions\UnknownCommandException;
use Dungeon\User;
use Illuminate\Console\Command;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dungeon {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a Dungeon command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->option('name');

        if (!$name) {
            $this->error('You must provide a username.');
            return;
        }

        $user = User::where('name', $name)->first();

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $this->comment('Type "quit" to stop.');

        while (1) {
            try {
                $cmd_in = $this->ask('Enter a command');

                if ($cmd_in === 'quit') {
                    $this->comment('Bye!');
                    return;
                }

                $response = CommandRunner::run($cmd_in, $user);

                $this->line($response->getMessage());
            } catch (UnknownCommandException $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
