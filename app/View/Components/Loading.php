<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loading extends Component{
  public string $loadingName;
  public function __construct($loadingName){
    $this->loadingName = $loadingName;
  }
  public function render(): View|Closure|string{
    return view('components.loading');
  }
}
