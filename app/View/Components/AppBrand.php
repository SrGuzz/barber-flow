<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <a href="/appointments" wire:navigate>
                    <!-- Hidden when collapsed -->
                    <div {{ $attributes->class(['hidden-when-collapsed', 'pb-0']) }}>
                        <div class="flex lg:justify-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-2/5 xs:w-2/5 lg:w-4/5" />
                        </div>
                    </div>

                    <!-- Display when collapsed -->
                    <div class="display-when-collapsed hidden">
                        <div class="flex justify-center mt-4 ml-1">
                            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-4/5"/>
                        </div>
                    </div>
                </a>
            HTML;
    }
}
