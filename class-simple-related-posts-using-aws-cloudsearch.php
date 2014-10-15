<?php
if ( class_exists('Simple_Related_Posts_Base') ) :
class Aws_Cloudsearch_Simple_Related_Posts extends Simple_Related_Posts_Base {	

	public function get_data_original( $num, $post_id ) {
		$aws_option = get_option( 'aws_cloudsearch_sirp_options' );
		
		if ( $num == '' ) {
			$option = get_option('sirp_options');
			$num = $option['display_num'];
		}

		$posttags = get_the_tags($post_id);
		if ( $posttags ) {
			foreach ( $posttags as $tag ) {

				$c = wp_remote_get($aws_option['endpoint'] . '?q='.urlencode($tag->name).'&size=100&return=_all_fields,_score&sort=_score+desc');
				if( !is_wp_error( $c ) && $c["response"]["code"] === 200 ) {
    				$results = json_decode($c["body"]);
				} else {
					// Handle error here.
				}

				if ( !is_array($results->hits->hit) ) 
					continue;

				$post_ids = array();
				foreach ( $results->hits->hit as $hit ) {
					$url = str_replace( 'http://lmedia.jp', home_url(), $hit->fields->permlink );
				    $post_ids[]['ID'] = url_to_postid($url);
				}
			}
		}
		
		arsort($post_ids);
		return array_slice($post_ids, 0, intval($num));
	}
}
endif;