<?php


abstract class Strategy {
    abstract public function suggestMove(Game $game);
}