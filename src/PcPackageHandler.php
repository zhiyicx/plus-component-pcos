<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Support\PackageHandler;

class PcPackageHandler extends PackageHandler
{
    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for pc, continue?')) {
            Comment::where('component', 'pc')->delete();
            $command->info('The Pc Component has been removed');
        }
    }

    public function installHandle($command)
    {
        // publish public assets
        $command->call('vendor:publish', [
            '--provider' => PcServiceProvider::class,
            '--tag' => 'public',
            '--force' => true,
        ]);

        // Run the database migrations
        $command->call('migrate');

        if ($command->confirm('Run seeder')) {
            // Run the database seeds.
            $command->call('db:seed', [
                '--class' => \PcDatabaseSeeder::class,
            ]);
        }
    }

    /**
     * Create a soft link to public.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function linkHandle($command)
    {
        if (! $command->confirm('Use a soft link to publish assets')) {
            return;
        }
        $this->unlink();
        $files = app('files');

        foreach ($this->getPaths() as $target => $link) {
            $parentDir = dirname($link);
            if (! $files->isDirectory($parentDir)) {
                $files->makeDirectory($parentDir);
            }
            $files->link($target, $link);
            $command->line(sprintf('<info>Created Link</info> <comment>[%s]</comment> <info>To</info> <comment>[%s]</comment>', $target, $link));
        }
        $command->info('Linking complete.');
    }

    /**
     * Delete links.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function unlink()
    {
        $files = app('files');
        foreach ($this->getPaths() as $path) {
            if (! $files->delete($path)) {
                $files->deleteDirectory($path, false);
            }
        }
    }

    /**
     * Get the Publish path,
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getPaths(): array
    {
        return PcServiceProvider::pathsToPublish(PcServiceProvider::class, 'public');
    }

}
