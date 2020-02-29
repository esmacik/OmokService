<?php

define("SIZE", 15);
define("STRATEGIES", ["Random", "Smart"]);

define("STRATEGY", "strategy");
define("PID", "pid");
define("MOVE", 'move');

$strategiesMap = array (
    'Smart' => 'SmartStrategy',
    'Random' => 'RandomStrategy'
);