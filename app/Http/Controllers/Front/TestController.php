<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * This function use to test get all image from a url
     *
     * @return array
     */
    public function test()
    {   
        //$url = 'https://www.milo.co.th/';
        $url = 'http://milo-au-v3.asiadigitalhub.com/champ-squad';
        $contents = file_get_contents($url);
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        @$dom->loadHTML($contents);
        libxml_clear_errors();
        $xpath = new \DOMXpath($dom);
        /*$nodes = $xpath->query("//div[@id='movie']");
        foreach ($nodes as $node) {
            $morenodes = $xpath->query(".//img ", $node);
            foreach($morenodes as $mode){
                dump($mode->getAttribute('src'));
            }
            
        }*/
        $arr_img = array();
        $nodes = $xpath->query(".//img ");
        foreach ($nodes as $node) {
            if(strpos($node->getAttribute('src'), '/files') != false) {
                $arr_img[] = $node->getAttribute('src');
            }
        }
        
        return view('frontend.test.list_images')->with(array('results' => $arr_img));
    }


}
