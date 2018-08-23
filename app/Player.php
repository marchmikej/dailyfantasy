<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function fanDuelPoints()
    {
    	return ($this->numberfire_fan_duel_projection + $this->rotogrinders_fan_duel_projection) / 2;
    }
}
