<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainHeader extends Component{

  public $is_path_file;
  public $sortingOrderInput;
  public $saveSortingButton;
  public $sortingOptionDialog;
  public $uploadDialog;
  public $fileInput;
  public $uploadButton;
  public $logoutButton;
  public $selectSortBy;
  public $sortByOptions;

  public function __construct(){
    $this->is_path_file = (new ViewFile())->is_file;
    $this->sortingOrderInput = generate_random_id();
    $this->saveSortingButton = generate_random_id();
    $this->sortingOptionDialog = generate_random_id();
    $this->uploadDialog = generate_random_id();
    $this->fileInput = generate_random_id();
    $this->uploadButton = generate_random_id();
    $this->logoutButton = generate_random_id();
    $this->selectSortBy = generate_random_id();
    $this->sortByOptions = [
      'name' => 'Name',
      'date' => 'Date'
    ];
  }

  public function render(): View|Closure|string{
    return view('components.main-header');
  }
}
