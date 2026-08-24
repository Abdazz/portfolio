<?php

namespace App\Contracts;

interface PdfRenderer
{
    /**
     * Render the given URL to a PDF file at $absolutePath.
     *
     * @param  string  $url  Fully-qualified URL to render (e.g. the print route)
     * @param  string  $absolutePath  Absolute filesystem path where the PDF will be written
     */
    public function render(string $url, string $absolutePath): void;
}
