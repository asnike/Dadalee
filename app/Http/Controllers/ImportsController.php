<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use \DOMDocument;
use \DOMXPath;

class ImportsController extends Controller
{
    //
    public function goodauction(Request $request){
        $site = 'http://www.goodauction.com';
        /*$site = 'http://naver.com/';*/
        $url = explode($site, $request->input('url'));
        $url = $url[1];
        /*$url = '/';*/
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

        $data = array();

        $DomXpath = new DOMXPath($dom);
        try{
            $nodes = $DomXpath->query('//td[@class="addr left"]');
            $data['addr'] = $nodes[0]->childNodes[0]->childNodes[0]->data;
            $data['addr'] = str_replace(' 외 1필지', '', explode(',', $data['addr'])[0]);

            $nodes = $DomXpath->query('//span[@class="f20"]');
            $data['no'] = $nodes[0]->childNodes[0]->data.'-'.$nodes[1]->childNodes[0]->data;

            $nodes = $DomXpath->query('//td[@class="class_info center"]');
            $data['type'] = $nodes[0]->childNodes[0]->childNodes[0]->data;

            $nodes = $DomXpath->query('//div[@class="view_gibon clear"]/table/tr/td[@class="center"]/text()');
            $data['land_size'] = $nodes[0]->data;
            $data['exclusive_size'] = $nodes[1]->data;

            $nodes = $DomXpath->query('//div[@class="view_gibon clear"]/table/tr/td[@class="money"]/text()');
            $data['identify_cost'] = $nodes[0]->data;
            $data['min_cost'] = $nodes[1]->data;

            $nodes = $DomXpath->query('//span[@class="blue bold no"]/text()');
            $data['date'] = $nodes[0]->data;

            $nodes = $DomXpath->query('//div[@class="view_build clear"]//td[@class="center"]/text()');
            $data['floor'] = $nodes[0]->data;

            $nodes = $DomXpath->query('//div[@class="tbl_buile class"]//div[@class="p_gam"]/span[@class="no"]/text()');
            $data['completed_at'] = $nodes[1]->data;

        }catch(\Exception $exception){
            return response()->json([
                'result'=>0,
                'message'=>'html parsing failed.'
            ]);
        }


        return response()->json([
            'result'=>1,
            'data'=>$data,
        ]);
    }
}
