<?php


class RandomStrategy extends Strategy {

    public function makeStrategyMove(Game $gameObject, $playerMoveX, $playerMoveY) {
        while ($gameObject->board[$randX = rand(0, SIZE - 1)][$randY = rand(0, SIZE - 1)] != 0)
            continue;
        return array($randX, $randY);
    }
}
