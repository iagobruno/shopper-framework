<?php

namespace Shopper\Framework\Components\Blade\Input;

use Illuminate\View\Component;

class RichText extends Component
{
    public function render()
    {
        return view('shopper::components.input.rich-text');
    }
}