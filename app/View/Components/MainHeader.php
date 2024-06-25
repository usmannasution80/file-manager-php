<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainHeader extends Component{
  /**
   * Create a new component instance.
   */
  public $links;

  public function __construct(){
    $links = explode('/', $_SERVER['REQUEST_URI']);
    $links_length = count($links);
    for($i=0; $i<$links_length; $i++){
      if($i === 0)
        $links[$i] = '/';
      else
        $links[$i] = preg_replace('/\\/+/i', '/', $links[$i-1] . '/' . $links[$i]);
    }
    $this->links = $links;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string{
    return view('components.main-header');
  }
}
