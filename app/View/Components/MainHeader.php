<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainHeader extends Component{

  public $links;
  public $is_path_file;
  public $sortingOrderInput;
  public $saveSortingButton;

  public function __construct(){
    $uri = $_SERVER['REQUEST_URI'];
    $uri = preg_replace('/\\/$/i', '', $uri);
    $links = explode('/', $uri);
    $links_length = count($links);
    for($i=0; $i<$links_length; $i++){
      if($i === 0)
        $links[$i] = '/';
      else
        $links[$i] = preg_replace('/\\/+/i', '/', $links[$i-1] . '/' . $links[$i]);
    }
    $this->links = $links;
    $this->is_path_file = (new ViewFile())->is_file;
    $this->sortingOrderInput = generate_random_id();
    $this->saveSortingButton = generate_random_id();
  }

  public function render(): View|Closure|string{
    return view('components.main-header');
  }
}
