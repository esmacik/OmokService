<?php


abstract class Strategy {

    abstract public function makeStrategyMove(Game $gameObject, $playerMoveX, $playerMoveY);
}