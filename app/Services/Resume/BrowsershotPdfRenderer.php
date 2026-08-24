<?php

namespace App\Services\Resume;

use App\Contracts\PdfRenderer;
use Spatie\Browsershot\Browsershot;

class BrowsershotPdfRenderer implements PdfRenderer
{
    public function render(string $url, string $absolutePath): void
    {
        $shot = Browsershot::url($url)
            ->noSandbox()
            ->addChromiumArguments(['--disable-gpu', '--disable-dev-shm-usage'])
            ->format('A4')
            ->margins(18, 16, 18, 16);

        $chromePath = config('services.browsershot.chrome_path');

        if (filled($chromePath)) {
            $shot->setChromePath($chromePath);
        }

        $nodePath = config('services.browsershot.node_path');
        $npmPath = config('services.browsershot.npm_path');

        if (filled($nodePath)) {
            $shot->setNodeBinary($nodePath);
        }

        if (filled($npmPath)) {
            $shot->setNpmBinary($npmPath);
        }

        $shot->savePdf($absolutePath);
    }
}
