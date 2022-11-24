<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $res = Website::all();
        return view('home', ['res' => $res]);
    }

    public function destroy(Website $website)
    {
        $website->delete();
        return redirect()->route('websites.index')
         ->withSuccess(__('Crawl deleted successfully.'));

    }
}
