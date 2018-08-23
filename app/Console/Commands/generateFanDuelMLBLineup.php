<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Player;
use App\Mlblineup;

class generateFanDuelMLBLineup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generateFanDuelMLBLineup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This generates the daily mlb FanDuel lineup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting lineup generator for FanDuel');
        $players = Player::where('position','<>','P')->get();
        $firstBasemen = Player::where('position','1B')->orwhere('position','C')->get();
        $secondBasemen = Player::where('position','2B')->get();
        $thirdBasemen = Player::where('position','3B')->get();
        $shortStops = Player::where('position','SS')->get();
        $pitchers = Player::where('position','P')->get();
        $outFielders = Player::where('position','OF')->get();

        foreach ($players as $player) {
            $this->info('Starting util:  ' . $player->last_name . "\n");
            foreach ($firstBasemen as $firstBaseman) {
                $this->info('Starting 1B:  ' . $firstBaseman->last_name . "\n");
                foreach ($secondBasemen as $secondBaseman) {
                    $this->info('Starting 2B:  ' . $secondBaseman->last_name . "\n");
                    foreach ($thirdBasemen as $thirdBaseman) {
                        $this->info('Starting 3B:  ' . $thirdBaseman->last_name . "\n");
                        foreach ($shortStops as $shortStop) {
                            $this->info('Starting SS:  ' . $shortStop->last_name . "\n");
                            foreach ($pitchers as $pitcher) {
                                $this->info('Starting P:  ' . $pitcher->last_name . "\n");
                                foreach ($outFielders as $of1) {
                                    //$this->info('Starting of1  ' . $of1->last_name . "\n");
                                    foreach ($outFielders as $of2) {
                                        //$this->info('Starting of2  ' . $of2->last_name . "\n");
                                        foreach ($outFielders as $of3) {
                                            //$this->info('Starting of3  ' . $of3->last_name . "\n");
                                            $salary = $player->fan_duel_cost + $firstBaseman->fan_duel_cost + $secondBaseman->fan_duel_cost + 
                                                $thirdBaseman->fan_duel_cost + $shortStop->fan_duel_cost + $pitcher->fan_duel_cost +
                                                $of1->fan_duel_cost + $of2->fan_duel_cost + $of3->fan_duel_cost;

                                            $points = $player->fanDuelPoints()+ $firstBaseman->fanDuelPoints() + $secondBaseman->fanDuelPoints() + 
                                                $thirdBaseman->fanDuelPoints() + $shortStop->fanDuelPoints() + $pitcher->fanDuelPoints() +
                                                $of1->fanDuelPoints() + $of2->fanDuelPoints() + $of3->fanDuelPoints();
/*
                                            if($salary < 35001) {
                                                $mlbLineup = new Mlblineup;
                                                $mlbLineup->fan_duel_points = $points;
                                                $mlbLineup->fan_duel_cost = $salary;
                                                $mlbLineup->first_base = $firstBaseman->id;
                                                $mlbLineup->second_base = $secondBaseman->id;
                                                $mlbLineup->third_base = $thirdBaseman->id;
                                                $mlbLineup->short_stop = $shortStop->id;
                                                $mlbLineup->of1 = $of1->id;
                                                $mlbLineup->of2 = $of2->id;
                                                $mlbLineup->of3 = $of3->id;
                                                $mlbLineup->util = $player->id;
                                                $mlbLineup->save(); 
                                            } */
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return 'done';
    }
}
