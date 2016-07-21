<?php 

namespace App\Scrapper;

use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;


class Scrapper
{
	private $link =  'http://www.porn.com';
	private $categories = [];
	private $categories_item = [];
	private $client;

	public function __construct(){
		$this->client = new Client();
		$this->categories = $this->scrapperCategories();
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function getCategoryItems($c)
	{
		if(!empty($this->categories)){
			$i = 0;
			foreach ($this->categories as $cat) {
				if($i >= $c) break;
				$n = explode('=>', $cat);
				$t = $n[0]; $l = $n[1];
				array_push($this->categories_item,[$t=>$this->parseCategories($l)]);
				$i++;
			}
		}

		return $this->categories_item;
	}

	private function sanitize(array $arr){
		for($i = 0, $len = count($arr); $i < $len; $i++) {
			if(!$arr[$i]){
				unset($arr[$i]);
			}
		}

		return $arr;
	} 

	private function scrapperCategories(){
		$client = $this->client;
		$crawler = $client->request('GET', $this->link);

		$nodes = $crawler->filter('ul.sFilters.initial li a')->each(function($node){
			if($node->attr('title') && $node->attr('href')){
				return $node->attr('title').'=>'.$node->attr('href');
			}
			return null;
		});

		return $this->sanitize($nodes);
	}

	private function parseCategories($link){
		$client = $this->client;
		$crawler = $client->request('GET', $link);

		$nodes = $crawler->filter('ul.listThumbs li.newest a.thumb')->each(function($node){
			if($node->attr('href')){
				return $node->attr('href').'=>'.$node->filter('img')->attr('src').'=>'.$node->siblings('a.title')->text();
			}
			return null;
		});

		return $this->sanitize($nodes);
	}

}