<?php

namespace Eighteen73\PwaManifest\Console\Commands;

use Eighteen73\PwaManifest\Events\BuildPwaManifest;
use Illuminate\Console\Command;

class BuildCommand extends Command
{
    protected $signature = 'pwa-manifest:build';

    protected $description = 'Build the web manifest and icon files';

    public function handle()
    {
        BuildPwaManifest::dispatch();
    }
}
