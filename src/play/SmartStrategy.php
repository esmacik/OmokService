<?php


class SmartStrategy extends Strategy {

    public function suggestMove(Game $game) {
        $c = $game->prevPlayerMove;
        $pointsToTry = [
            [$c[0]-1, $c[1]-1],[$c[0], $c[1]-1],[$c[0]+1, $c[1]-1],
            [$c[0]-1, $c[1]],                   [$c[0]+1, $c[1]],
            [$c[0]-1, $c[1]+1],[$c[0], $c[1]+1],[$c[0]+1, $c[1]+1]
        ];

        for ($i = 0; $i < count($pointsToTry); $i += 1) {
            $attempt = $pointsToTry[$i];
            $attemptOpposite = $pointsToTry[count($pointsToTry) - 1 - $i];
            if ($attemptOpposite[0] < 0 || $attemptOpposite[1] < 0 || $attemptOpposite[0] >= SIZE || $attemptOpposite[1] >= SIZE)
                continue;

            if ($game->board[$attempt[0]][$attempt[1]] == 1) {
                if ($game->board[$attemptOpposite[0]][$attemptOpposite[1]] == 0) {
                    return [$attemptOpposite[0], $attemptOpposite[1]];
                }
            }
        }

        foreach ($pointsToTry as $attempt) {
            if ($attempt[0] < 0 || $attempt[1] < 0 || $attempt[0] >= SIZE || $attempt[1] >= SIZE)
                continue;
            if ($game->board[$attempt[0]][$attempt[1]] == 0) {
                return [$attempt[0], $attempt[1]];
            }
        }

        return (new RandomStrategy())->$this->suggestMove($this);
    }
}
