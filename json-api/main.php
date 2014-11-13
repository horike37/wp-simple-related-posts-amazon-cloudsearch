<?php
function aws_cloud_search_doc_api_init() {
	global $aws_cloud_search_doc_api;

	$aws_cloud_search_doc_api = new AWS_Cloud_Search_Doc_API();
	add_filter( 'json_endpoints', array( $aws_cloud_search_doc_api, 'register_routes' ) );
}
add_action( 'wp_json_server_before_serve', 'aws_cloud_search_doc_api_init' );

class AWS_Cloud_Search_Doc_API {
	public function register_routes( $routes ) {
		$routes['/aws_cloudsearch/(?P<count>\d+)'] = array(
			array( array( $this, 'get_posts'), WP_JSON_Server::READABLE ),
		);

		return $routes;
	}
	
	public function get_posts($count) {
		$args = array(
					'posts_per_page' => $count,
					'post_type' => 'post',
					'post_status' => 'publish'
					);
		$posts = get_posts($args);
		
		if ( empty($posts) )
			return;
		
		$results = array();
		$json = array(
					'type' => 'add',
					'id' => '',
					'fields' => 
						array(
							'id' => '',
							'title' => '',
							'content' => '',
						)
					);
		foreach ( $posts as $post ) {
			$json['id'] = $post->ID;
			$json['fields']['id'] = $post->ID;
			$json['fields']['title'] = $post->post_title;
			$json['fields']['content'] = $post->post_content;
			$results[] = $json;
		}
		return $results;
	}
	// ...
}