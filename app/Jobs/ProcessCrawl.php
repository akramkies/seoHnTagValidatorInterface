<?php

namespace App\Jobs;

use App\Http\Controllers\CrawlController;
use App\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Globalis\SeoHnTagValidator\SeoHnTagValidator;
use Illuminate\Support\Facades\DB;

class ProcessCrawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $id;
    private $onlyErrors;
    private $concurrent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $onlyErrors, $concurrent)
    {
        $this->id = $id;
        $this->onlyErrors = $onlyErrors;
        $this->concurrent = $concurrent;
    }

    /**
     * Execute the job.
     *
     * @param  string  $url
     * @return void
     */
    public function handle()
    {
        $validator  = new SeoHnTagValidator();
        $ws = DB::table('websites')->where('id', $this->id)->first();

        // // Start Crawling
        $res = $validator->validateWebSite($ws->url, $this->onlyErrors, $this->concurrent);

        for ($i = 0; $i < count($res); $i++) {
            $errors = "";
            foreach( $res[$i]['errors'] as $error ) {
                $errors = $errors . $error . " \ ";
            }
            if($errors === "") $errors = "0 errors";
            $res[$i]['errors'] = $errors;
            $tags = "";
            for($j = 0 ; $j < count($res[$i]['tags']); $j++ ) {
                switch($res[$i]['tags'][$j]['tag']):
                    case 'h1':
                        $tags = $tags . ' --' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    case 'h2':
                        $tags = $tags . ' --' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    case 'h3':
                        $tags = $tags . ' ----' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    case 'h4':
                        $tags = $tags . ' ------' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    case 'h5':
                        $tags = $tags . ' --------' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    case 'h6':
                        $tags = $tags . ' ----------' . $res[$i]['tags'][$j]['tag'] . "\n";
                        break;
                    default:
                        break;
                endswitch;
            }
            $res[$i]['website_id'] = $this->id;
            $res[$i]['is_valid'] = $res[$i]['is_valid'] === 'true' ? true : false;
            $res[$i]['tags'] = $tags;
            Url::Create($res[$i]);
        }

        DB::table('websites')
              ->where('id', $this->id)
              ->update(['state' => CrawlController::COMPLETE, 'nb_crawled_page' => count($res)]);
    }
}
