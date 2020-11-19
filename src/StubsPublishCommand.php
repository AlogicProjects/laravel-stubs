<?php

namespace AlogicProjects\LaravelStubs;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class StubsPublishCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'alogic-stub:publish {--force : Overwrite existing}';


    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }

        collect(File::files(__DIR__ . '/../stubs'))->each(function (SplFileInfo $file) use ($stubsPath) {
            $sourcePath = $file->getPathname();

            $targetPath = $stubsPath . "/{$file->getFilename()}";

            if (! file_exists($targetPath) || $this->option('force')) {
                file_put_contents($targetPath, file_get_contents($sourcePath));
            }
        });

        $this->info('Ok');
    }
}
