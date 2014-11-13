<?php
if ( class_exists('Simple_Related_Posts_Base') ) :
class Aws_Cloudsearch_Simple_Related_Posts extends Simple_Related_Posts_Base {	

	public function get_data_original( $num, $post_id ) {
		$aws_option = get_option( 'aws_cloudsearch_sirp_options' );

		if ( $num == '' ) {
			$option = get_option( 'sirp_options' );
			$num    = $option['display_num'];
		}

		$keywords = array();
		$mecab = new MeCab_Tagger();
		
		//タイトル
		$text = get_the_title($post_id);
		for($node=$mecab->parseToNode($text); $node; $node=$node->getNext()){
			if($node->getStat() != 2 && $node->getStat() != 3){
				if($node->posid >= 37 && $node->posid <= 66) {
		
					if(!isset($keywords[$node->surface])) {
						$keywords[$node->surface] = 5;
					} else{
						$keywords[$node->surface] = $keywords[$node->surface]+5;
					}
				}
			}
		}
		
		
		//本文
		$text = get_post($post_id)->post_content;
		for($node=$mecab->parseToNode($text); $node; $node=$node->getNext()){
			if($node->getStat() != 2 && $node->getStat() != 3){
				if($node->posid >= 37 && $node->posid <= 66) {
		
					if(!isset($keywords[$node->surface])) {
						$keywords[$node->surface] = 1;
					} else{
						$keywords[$node->surface] = $keywords[$node->surface]+1;
					}
				}
			}
		}
		
		
		arsort($keywords);
		$keywords = array_slice($keywords, 0, 5);
		$result = '';
		foreach( $keywords as $key => $val ) {
			$result .= ' ' . $key;
		}
		
		
		$search_url = $aws_option['endpoint'] . '?q='.urlencode($result).'&q.parser=simple&q.options=%7B%22defaultOperator%22%3A%22or%22%2C%22fields%22%3A%5B%22title%5E5%22%2C%22content%5E2%22%5D%2C%22operators%22%3A%5B%22and%22%2C%22escape%22%2C%22fuzzy%22%2C%22near%22%2C%22not%22%2C%22or%22%2C%22phrase%22%2C%22precedence%22%2C%22prefix%22%2C%22whitespace%22%5D%7D&return=_all_fields%2C_score&highlight.content=%7B%22max_phrases%22%3A3%2C%22format%22%3A%22text%22%2C%22pre_tag%22%3A%22*%23*%22%2C%22post_tag%22%3A%22*%25*%22%7D&highlight.title=%7B%22max_phrases%22%3A3%2C%22format%22%3A%22text%22%2C%22pre_tag%22%3A%22*%23*%22%2C%22post_tag%22%3A%22*%25*%22%7D&sort=_score+desc';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $search_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		
		$c = curl_exec($ch);
		curl_close($ch);
		
		$results = json_decode($c);
		
		$search_results = array();
		foreach ( $results->hits->hit as $hit) {
			$search_results[] = $hit->fields->id;
		}
		$post_ids = array();
		foreach ( $search_results as $id ) {
			if ( $post_id == $id )
				continue;

			$post_ids[]['ID'] = $id;
		}


		return array_slice($post_ids, 0, intval($num));
	}
}
endif;