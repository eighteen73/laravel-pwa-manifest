<?php

namespace Eighteen73\PwaManifest\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class BuildPwaManifest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $rootPath;

    private string $rootUri;

    private array $manifest;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        if (! $this->getPaths()) {
            Log::error('Error building web manifest files');

            return;
        }
        $this->cleanup(); // TODO cleanup after creating new files to prevent empty manifest for a second
        $this->manifestScaffold();
        $this->createIcons();
        $this->writeManifest();
    }

    private function getPaths(): bool
    {
        $this->rootPath = rtrim(config('pwa-manifest.root_path'), '/');
        $this->rootUri = rtrim(config('pwa-manifest.root_uri'), '/');

        // Missing rootPath config
        if (empty($this->rootPath)) {
            Log::debug('Missing config "pwa-manifest.root_path"');

            return false;
        }

        // Missing rootPath
        if (
            ! File::isDirectory($this->rootPath)
            &&
            ! File::makeDirectory(path: $this->rootPath, recursive: true)
        ) {
            Log::debug('Path specified in "pwa-manifest.root_path" cannot be created: '.$this->rootPath);

            return false;
        }

        // Unwritable rootPath
        if (! File::isWritable($this->rootPath)) {
            Log::debug('Path specified in "pwa-manifest.root_path" is not writable: '.$this->rootPath);

            return false;
        }

        // Suspicious rootPath that we don't want to wipe!
        if (File::directories($this->rootPath)) {
            Log::debug('Path specified in "pwa-manifest.root_path" has subdirs so does not look safe to use: '.$this->rootPath);

            return false;
        }
        $allowed_files_regex = '/^manifest\.json|icon-[0-9]+\.png$/';
        foreach (File::allFiles($this->rootPath) as $filename) {
            if (! preg_match($allowed_files_regex, $filename->getFilename())) {
                Log::debug('Path specified in "pwa-manifest.root_path" has unknown files so does not look safe to use: '.$this->rootPath);

                return false;
            }
        }

        // Missing source icon config
        if (! config('pwa-manifest.icons.primary')) {
            Log::debug('Missing config "pwa-manifest.icons.primary"');

            return false;
        }

        // Missing source icon file
        if (! File::exists(config('pwa-manifest.icons.primary'))) {
            Log::debug('Source icon from "pwa-manifest.icons.primary" does not exist: '.config('pwa-manifest.icons.primary'));

            return false;
        }

        // Missing rootUri config
        if (empty($this->rootUri)) {
            Log::debug('Missing config "pwa-manifest.root_uri"');

            return false;
        }

        return true;
    }

    private function cleanup()
    {
        if (file_exists("{$this->rootPath}/manifest.json")) {
            File::cleanDirectory($this->rootPath);
        }
    }

    private function manifestScaffold()
    {
        $this->manifest = [
            'background_color' => config('pwa-manifest.theme_color', '#ffffff'),
            'description' => config('pwa-manifest.description'),
            'display' => 'standalone',
            'id' => config('app.url'),
            'name' => config('pwa-manifest.name'),
            'orientation' => 'any',
            'scope' => config('app.url'),
            'short_name' => config('pwa-manifest.short_name'),
            'start_url' => config('app.url'),
            'theme_color' => config('pwa-manifest.theme_color', '#ffffff'),
        ];

        // Apply config overrides
        foreach (config('pwa-manifest.manifest_overrides') as $option => $override) {
            $this->manifest[$option] = $override;
        }

        // Remove empty options
        $this->manifest = array_filter($this->manifest);
    }

    private function createIcons()
    {
        $source = config('pwa-manifest.icons.primary');
        if (! File::exists($source) || ! File::isFile($source)) {
            return;
        }

        $img = Image::make($source)->backup();

        // Standard WPA icons
        foreach ([192, 512] as $size) {
            $url = $this->makeIcon($img, $size);
            $this->manifest['icons'][] = [
                'src' => $url,
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'any',
            ];
        }

        // Standard favicons and apple-touch-icon
        foreach ([16, 32, 180] as $size) {
            $this->makeIcon($img, $size);
        }

        // Shortcut icons
        if (isset($this->manifest['shortcuts'])) {
            $icon_size = 96;
            $url = $this->makeIcon($img, $icon_size);
            foreach ($this->manifest['shortcuts'] as &$shortcut) {
                $shortcut['icons'] = [
                    [
                        'src' => $url,
                        'sizes' => "{$icon_size}x{$icon_size}",
                        'type' => 'image/png',
                        'purpose' => 'any',
                    ],
                ];
            }
        }
    }

    private function makeIcon(\Intervention\Image\Image &$img, int $size, ?string $filename = null): string
    {
        $filename = $filename ? "{$filename}.png" : "icon-{$size}.png";
        if (! File::exists("{$this->rootPath}/{$filename}")) {
            $img->reset()->resize($size, $size)->save("{$this->rootPath}/{$filename}");
        }

        return "{$this->rootUri}/{$filename}";
    }

    private function writeManifest()
    {
        $filepath = "{$this->rootPath}/manifest.json";
        File::put($filepath, json_encode($this->manifest, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }
}
