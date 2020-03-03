<?php


class RandomStrategy extends Strategy {

    public function suggestMove(Game $game) {
        while ($game->board[$randX = rand(0, SIZE - 1)][$randY = rand(0, SIZE - 1)] != 0)
            continue;
        return array($randX, $randY);
    }
}
