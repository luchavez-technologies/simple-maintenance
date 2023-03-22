<?php

namespace Luchavez\SimpleMaintenance\Console\Commands;

use Luchavez\StarterKit\Traits\UsesCommandCustomMessagesTrait;
use Illuminate\Console\Command;
use Spatie\Tags\TagsServiceProvider;

/**
 * Class InstallSimpleMaintenanceCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class InstallSimpleMaintenanceCommand extends Command
{
    use UsesCommandCustomMessagesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'maintenance:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute package setup.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => TagsServiceProvider::class,
            '--tag' => 'tags-migrations',
        ]);

        $this->call('migrate');

        $this->call('vendor:publish', [
            '--provider' => TagsServiceProvider::class,
            '--tag' => 'tags-config',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'simple-maintenance.config',
        ]);

        return self::SUCCESS;
    }
}
