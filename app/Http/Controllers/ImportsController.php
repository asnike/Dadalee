<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use \DOMDocument;
use \DOMXPath;
use Symfony\Component\DomCrawler\Crawler;

class ImportsController extends Controller
{
    //
    public function goodauction(Request $request){
        $site = 'http://www.goodauction.com';
        $url = explode($site, $request->input('url'));
        $url = $url[1];
        $client = new Client([
            'base_uri'=>$site,
            'timeout'=>2,
        ]);
        try{
            $res = $client->get($url);
        }catch(ConnectException $e){
            throw new \RuntimeException(
                $e->getHandlerContext()['error']
            );
        }
        $result = $res->getBody();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($result);
        libxml_clear_errors();

        $DomXpath = new DOMXPath($dom);
        $nodes = $DomXpath->query('//td[@class="addr left"]');

        dd($nodes);
    }
}
