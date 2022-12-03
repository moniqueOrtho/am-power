<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Navigation extends Component
{
    /**
     * The menu state.
     * @var string open or closed
     */
     public $state;

    /**
     * Create a new component instance.
     * @param string $state
     * @return void
     */
    public function __construct($state )
    {
        $this->state = $state;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.navigation');
    }
}
