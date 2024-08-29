<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SettingDialog extends Component{

  public $id;
  public $saveButton;
  public $showStartWithPoint;
  public $showDoublePoint;
  public $autoplayMedia;
  public $nextMediaAutoplay;

  public function __construct($id){
    $this->id = $id;
    $this->saveButton = generate_random_id();
    $this->showStartWithPoint = generate_random_id();
    $this->showDoublePoint = generate_random_id();
    $this->autoplayMedia = generate_random_id();
    $this->nextMediaAutoplay = generate_random_id();
  }

  public function render(): View|Closure|string{
    return view('components.setting-dialog');
  }
}
