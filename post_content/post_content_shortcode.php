<?php
/**
 * trm_wp_functions.
 * joefritsch
 * 20.11.17
 */
/**
 * short code insert post content
 * usage samples:
 *  - [post-content slug="mode-aus-italien" content="thumbnail,title,main" permalink="thumbnail" more_text="<br>Weiterlesen..." post_type="page"]
 *
 * inserted class: trm-insert-post-content $slug
 * @param $atts
 */
function trm_post_content_shortcode($atts){
	$args = shortcode_atts( array(
		'post_type' => 'post',
		'slug' => '',
		'id' => false,
		'content' => 'content',     // possible tags: title, content, main, thumbnail
		'content_filter' => 'the_content',
		'permalink' => 'false',     // all: link whole content, otherwise: title, content, thumbnail, false
		'class' => '',
		'more_text' => 'Weiterlesen&nbsp;...'
	), $atts, 'page-content' );
	global $post;
	$the_post_slug = $post->post_name;

	if($args['slug']){
		$getpost = get_post_by_slug($args['slug'],$args['post_type']);
	}else{
		$getpost = $post;
	}
	$res = '<div class="trm-insert-post-content '.$the_post_slug.' '.$args['class'].'">';
	if($args['permalink'] == 'all'){
		$res .= '<a href="'.get_the_permalink( $getpost).'">';
	}
	$content_array = explode(",",$args['content']);
	$permalink_array = explode(",",$args['permalink']);
	foreach($content_array as $content){
		if($content == 'thumbnail'){
			if(in_array('thumbnail',$permalink_array)){
				$res .= '<a href="'.get_the_permalink( $getpost).'">'.get_the_post_thumbnail( $getpost).'</a>';
			}else{
				$res .= get_the_post_thumbnail( $getpost);
			}
		}
		if($content == 'title'){
			if(in_array('title',$permalink_array)){
				$res .= '<h2><a href="'.get_the_permalink($getpost).'">'.$getpost->post_title.'</a></h2>';
			}else{
				$res .= '<h2>'.$getpost->post_title.'</h2>';
			}
		}
		if($content == 'content'){
			if(in_array('content',$permalink_array)){
				$res .= '<a href="'.get_the_permalink($getpost).'">'.$getpost->post_content.'</a>';
			}else{
				$res .= $getpost->post_content;
			}
		}
		if($content == 'main'){
			$result = get_extended($getpost->post_content) ;
			if ( in_array( 'main', $permalink_array ) ) {
				$res .= '<a href="' . get_the_permalink( $getpost ) . '">' . $result['main'] . '</a>';
			} else {
				$res .= $result['main'];
				if(!empty($result['extended'])) {
					$res .= ' <a href="' . get_the_permalink( $getpost ) . '">' . str_replace( " ", '&nbsp;', $args['more_text'] ) . '</a>';
				}
			}
		}
	}
	if($args['permalink'] == 'all'){
		$res .= '</a>';
	}

	$res .= '</div>';
	$res = !empty($args['content_filter']) ? apply_filters($args['content_filter'],$res) : $res;

	return $res;
}

/**
 * helper function
 * @param $slug
 * @param $post_type
 * @return mixed
 * @throws Exception
 */
function get_post_by_slug($slug, $post_type){
	$posts = get_posts(array(
		'name' => $slug,
		'posts_per_page' => 1,
		'post_type' => $post_type,
		'post_status' => 'publish'
	));

	if( !$posts ) {
		throw new Exception("No Such Post By Specified Slug: ".$slug);
	}
	return $posts[0];
}