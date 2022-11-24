<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCrawl;
use App\Models\Url;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrawlController extends Controller
{
    const IN_PROGRESS = 'in_progress';
    const COMPLETE = 'complete';

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
        $res = [];

        $validated = $request->validate([
            'url' => 'required|url',
            'concurrent' => 'numeric|max:30|min:3',
            'agree' => ''

        ]);

        $onlyErrors = $request->agree !== null ? 1 : 0;
        $concurrent = $validated['concurrent'];

        // Insert new crawl
        $website = [];
        $website['url'] = $validated['url'];
        $website['state'] = $this::IN_PROGRESS;
        $website['nb_crawled_page'] = 0;
        $ws = Website::Create($website);

        ProcessCrawl::dispatch($ws->id, $onlyErrors, $concurrent);

        return view('validator.index',
            [
                'res' => $res,
                'statut' => $this::IN_PROGRESS,
                'url' => $validated['url']

            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ws = DB::table('websites')->where('id', $id)->first();
        $res = DB::table('urls')->where('website_id', $id)->get();

        if (is_null($ws)) {
            return view('404');
        }
        foreach ($res as $value) {
            $value->tags = explode(' ', $value->tags);
        }

        return view('validator.index',
        [
            'res' => $res,
            'statut' => $ws->state,
            'url' => $ws->url
        ]);
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
