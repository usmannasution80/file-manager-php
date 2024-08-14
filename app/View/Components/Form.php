<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component{

  public $value;
  public $type;
  public $placeholder;
  public $label;
  public $inputId;
  public $containerClass;
  public $inputClass;
  public $labelClass;
  public $isCheck;
  public $buttonLabel;
  public $name;
  public $inline;

  public function __construct(

    $value = '',
    $type = 'text',
    $placeholder = '',
    $buttonLabel = null,
    $label = null,
    $inputId = null,
    $name = null,
    $containerClass = '',
    $inline = false,

  ){

    $this->value = $value;
    $this->placeholder = $placeholder;
    $this->type = $type;
    $this->label = $label;
    $this->inputId = $inputId;
    $this->isCheck = $type == 'checkbox' || $type == 'radio';
    $this->inputId = $inputId ? $inputId : generate_random_id();
    $this->buttonLabel = $buttonLabel;
    $this->name = $name;
    $this->containerClass = $containerClass;
    $this->inline = $inline;
    $this->setElementsClasses();

  }

  public function render(): View|Closure|string{
    return view('components.form');
  }

  public function setElementsClasses(){
    if($this->isCheck){
      if($this->buttonLabel){
        $this->inputClass = 'btn-check';
        $this->labelClass = $this->buttonLabel;
      }else{
        $this->containerClass .= ' form-check';
        $this->inputClass = 'form-check-input';
        $this->labelClass = 'form-check-label';
      }
      if($this->inline)
        $this->containerClass .= ' form-check-inline';
    }else{
      $this->containerClass .= ' form-group';
      $this->inputClass = 'form-control';
      $this->labelClass = 'form-label';
      if($this->type === 'file')
        $this->inputClass .= '-file';
    }
  }

}
