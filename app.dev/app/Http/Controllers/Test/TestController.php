<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class TestController extends Controller
{
	private $link =  'http://www.example.com';
	private $categories = [];

	private function scrapper($link)
	{
		$client = new Client();
		$crawler = $client->request('GET', $link);

		$nodes = $crawler->filter('ul.sFilters.initial li a')->each(function($node){
			if($node->attr('title') && $node->attr('href')){
				return $node->attr('title').'=>'.$node->attr('href');
			}	
		});

		return $nodes;
	}

	public function index()
	{
		$nodes = $this->scrapper($this->link);
		$links = '';

		if(!empty($nodes)){
			foreach ($nodes as $node) {
				if($node){
					$n = explode('=>',$node);
					$title = $n[0];
					$link = $this->link.$n[1];

					if($link != $this->link){
						array_push($this->categories, ['title'=>$title,'link'=>$link]);
						$links .= '<a href="'.$link.'">'.$title.'</a><br>';
					}
				}
			}
		}

		return $links;
	}
}

