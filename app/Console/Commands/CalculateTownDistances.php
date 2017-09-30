<?php

namespace App\Console\Commands;

use App\Listing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateTownDistances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'towndistances:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and store town distances for listings without one';

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
        $this->info(Carbon::now()->format('[Y-m-d H:i:s] ') . 'Calculating town distances...');

        $listings = Listing::whereNull('town_distance')->orderBy('area_id', 'desc')->get();

        $api_key = env('GOOGLE_API_KEY');
        if ($api_key == null){
            $this->warn(Carbon::now()->format('[Y-m-d H:i:s] ') . 'No GOOGLE_API_KEY .env variable set. Cannot calculate town distances without it.');
            return;
        }

        $count = count($listings);
        $count > 0
            ? $this->info(Carbon::now()->format('[Y-m-d H:i:s] ') . $count . ' listings with no previously calculated town distance found.')
            : $this->info(Carbon::now()->format('[Y-m-d H:i:s] ') . 'No listings with no previously calculated town distance found.');

        $count_success = 0; $count_failed = 0;
        foreach ($listings as $listing) {
            $postcode = str_replace(' ', '+', $listing->postcode);
            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $postcode . '&destinations=' . $listing->area->name . ',UK&mode=transit&departure_time=1506423600&key=' . $api_key;
            $json = file_get_contents($url);
            $data = json_decode($json);
            $town_distance = round($data->rows[0]->elements[0]->duration->value/60);

            $this->line(Carbon::now()->format('[Y-m-d H:i:s] ') . '    ' . $listing->postcode . ' to ' . $listing->area->name . ' : ' . $town_distance . ' minutes');

            $listing->town_distance = $town_distance;
            $listing->updated_at = Carbon::now();
            $success = $listing->save();

            $success ? $count_success++ : $count_failed++;
        }

        if ($count_success + $count_failed > 0) {
            $this->info(Carbon::now()->format('[Y-m-d H:i:s] ') . 'Town distances calculation complete.');
            $this->line(Carbon::now()->format('[Y-m-d H:i:s] ') . '    Success: ' . $count_success . ' listings');
            if ($count_failed > 0)
                $this->warn(Carbon::now()->format('[Y-m-d H:i:s] ') . '    Failed: ' . $count_failed . ' listings');
        }
    }
}
