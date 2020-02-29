<?php


class SmartStrategy extends Strategy {

    public function makeStrategyMove(Game $gameObject, $playerMoveX, $playerMoveY) {
        $playerMadeRow = $gameObject->checkGameWon($playerMoveX, $playerMoveY, 1);
        if (count($playerMadeRow) / 2 == 4) { // Player made 4 in a row
            // Block potential player win here
        } elseif (count($playerMadeRow) / 2 == 3) { //Player made 3 in a row
            // and here
        }

    }
}
