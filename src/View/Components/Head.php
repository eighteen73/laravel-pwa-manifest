<?php

namespace Eighteen73\PwaManifest\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class Head extends Component
{
    public function render(): View
    {
        $name = config('pwa-manifest.name');

        return view('pwa-manifest::components.head', [
            'has_files' => File::exists(config('pwa-manifest.root_path').'/manifest.json'),
            'base_uri' => config('pwa-manifest.root_uri'),
            'name' => $name,
        ]);
    }
}
