<?php

function generate_random_id(){
  static $last = 0;
  return 'random-' . ($last++);
}