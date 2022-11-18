<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Website;
use Illuminate\Http\Request;
use Globalis\SeoHnTagValidator\SeoHnTagValidator;
use App\Jobs\ProcessPodcast;

class CrawlController extends Controller
{
    const IN_PROGRESS = 'in_progress';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('validator.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'concurrent' => 'numeric'

        ]);

        $validator  = new SeoHnTagValidator();

        // Insert new crawl
        $website = [];
        $website['url'] = $validated['url'];
        $website['state'] = $this::IN_PROGRESS;
        $website['nb_crawled_page'] = 0;
        Website::Create($website);

        // Start Crawling
        $res = $validator->validateWebSite($validated['url']);

        for ($i = 0; $i < count($res); $i++) {
            $errors = "";
            foreach( $res[$i]['errors'] as $error ) {
                $errors = $errors . $error . " \ ";
            }
            if($errors === "") $errors = "0 errors";
            $res[$i]['errors'] = $errors;
            for($j = 0 ; $j < count($res[$i]['tags']); $j++ ) {
                switch($res[$i]['tags'][$j]['tag']):
                    case 'h2':
                        $res[$i]['tags'][$j]['tag'] =  '--' . $res[$i]['tags'][$j]['tag'];
                        break;
                    case 'h3':
                        $res[$i]['tags'][$j]['tag'] =  '----' . $res[$i]['tags'][$j]['tag'];
                        break;
                    case 'h4':
                        $res[$i]['tags'][$j]['tag'] =  '------' . $res[$i]['tags'][$j]['tag'];
                        break;
                    case 'h5':
                        $res[$i]['tags'][$j]['tag'] =  '--------' . $res[$i]['tags'][$j]['tag'];
                        break;
                    case 'h6':
                        $res[$i]['tags'][$j]['tag'] =  '----------' . $res[$i]['tags'][$j]['tag'];
                        break;
                    default:
                        break;
                endswitch;
            }
        }

        return view('validator.index', ['res' => $res]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
