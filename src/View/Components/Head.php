<?php

namespace Eighteen73\PwaManifest\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Head extends Component
{
    public function render(): View
    {
        $name = config('pwa-manifest.manifest.short_name', config('pwa-manifest.manifest.name'));

        return view('pwa-manifest::components.head', [
            'base_uri' => config('pwa-manifest.root_uri'),
            'name' => $name,
        ]);
    }
}
