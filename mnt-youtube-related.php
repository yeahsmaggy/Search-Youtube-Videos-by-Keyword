<?php
/*
Plugin Name: MNT Youtube related
Plugin URI:
Description:
Version: 0.1
Author: Andy Welch
Author URI: http://www.codebloc.co.uk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/






if ( ! class_exists( 'Foo' ) ){
	class Foo {
		public $videos=[];
	function __construct() {
		add_action( 'template_redirect', array( &$this, 'my_hook_implementation' ) );
	}

	function my_hook_implementation() {
		global $post;


		$yoastfocuskeyword = get_post_meta( $post->ID, '_yoast_wpseo_focuskw', true );


		if(!empty($yoastfocuskeyword)){
					$q=$yoastfocuskeyword;
		}else{
			$q=$post->post_title;

		}

		require_once 'vendor/autoload.php';
		$DEVELOPER_KEY = '';


		$client = new Google_Client();
		$client->setDeveloperKey( $DEVELOPER_KEY );


		$youtube = new Google_Service_YouTube( $client );
		try {
			$searchResponse = $youtube->search->listSearch( 'id,snippet', array(
					'q' => $q,
					'maxResults' => 5,
				) );


			$videos = [];
			$channels = '';
			$playlists = '';


			foreach ( $searchResponse['items'] as $searchResult ) {
				switch ( $searchResult['id']['kind'] ) {
				case 'youtube#video':
					$this->videos[]=['videoid'=>$searchResult['id']['videoId'],
					'thumb'=> 'http://img.youtube.com/vi/' . $searchResult['id']['videoId'],
					'title'=>$searchResult['snippet']['title']
					 ]; 
					break;
				}
			}


		}
		catch ( Google_Service_Exception $e ) {
			$htmlBody .= sprintf( '<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars( $e->getMessage() ) );
		}
		catch ( Google_Exception $e ) {
			$htmlBody .= sprintf( '<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars( $e->getMessage() ) );
		} 
		return $this->videos;

	}

	public function my_special_method() {
		// does something else
	}
	}
}
if ( class_exists( 'Foo' ) ){
	$MyFoo = new Foo();
}
?>
