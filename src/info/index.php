<?php // info/index.php
// Returns a json of supported size and strategies
require "constants.php";

// Default values returned, per GameInfo class constructor
echo json_encode(array(
    "size"=>SIZE,
    "strategies"=>STRATEGIES));