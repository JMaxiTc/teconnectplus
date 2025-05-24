<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PDFServiceProvider extends ServiceProvider
{
    /**
     * Register the PDF services.
     */    public function register(): void
    {
        // No need to register anything here as Barryvdh\DomPDF\ServiceProvider
        // already registers everything we need
    }
}
