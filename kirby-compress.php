<?php

if(!c::get('plugin.compress')) return;

load(array(
  'iksi\\compress\\response' => __DIR__ . DS . 'lib' . DS . 'response.php'
));

kirby()->set('component', 'response', 'Iksi\\Compress\\Response');
