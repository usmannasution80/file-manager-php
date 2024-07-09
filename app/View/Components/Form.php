<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component{

  public $is_check;
  public $value;
  public $type;
  public $placeholder;
  public $label;
  public $input_id;

  public function __construct(
    $isCheck = null,
    $value = '',
    $type = 'text',
    $placeholder = '',
    $label = null,
    $inputId = null
  ){

    $this->is_check = $isCheck;
    $this->value = $value;
    $this->placeholder = $placeholder;
    $this->type = $type;
    $this->label = $label;
    $this->input_id = $inputId;
    if($this->is_check && $this->type === 'text')
      $this->type = 'checkbox';

  }

  public function render(): View|Closure|string{
    return view('components.form');
  }
}
