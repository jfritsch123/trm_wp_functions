<?php
/**
 * trm_wp_functions.
 * joefritsch
 * 20.11.17
 */
/**
 * short code insert post content
 * @param $atts
 */
function trm_post_content_shortcode($atts){
	$args = shortcode_atts( array(
		'post_type' => 'post',
		'slug' => '',
		'id' => false,
		'content' => 'content',     // possible tags: title, content, main, thumbnail
		'permalink' => 'false',     // all: link whole content, otherwise: title, content, thumbnail, false
		'class' => '',
		'more_text' => 'Weiterlesen&nbsp;...'
	), $atts, 'page-content' );
	global $post;
	$the_post = $post;
	$the_post_slug = $post->post_name;
	$post = 'empty';

	if($args['slug']){
		$post = get_post_by_slug($args['slug'],$args['post_type']);
	}
	$res = '<div class="trm-insert-post-content '.$the_post_slug.' '.$args['class'].'">';
	if($args['permalink'] == 'all'){
		$res .= '<a href="'.get_the_permalink( $post).'">';
	}
	$content_array = explode(",",$args['content']);
	$permalink_array = explode(",",$args['permalink']);
	foreach($content_array as $content){
		if($content == 'thumbnail'){
			if(in_array('thumbnail',$permalink_array)){
				$res .= '<a href="'.get_the_permalink( $post).'">'.get_the_post_thumbnail( $post).'</a>';
			}else{
				$res .= get_the_post_thumbnail( $post);
			}
		}
		if($content == 'title'){
			if(in_array('title',$permalink_array)){
				$res .= '<h3><a href="'.get_the_permalink( $post).'">'.$post->post_title.'</a></h3>';
			}else{
				$res .= '<h3>'.$post->post_title.'</h3>';
			}
		}
		if($content == 'content'){
			if(in_array('content',$permalink_array)){
				$res .= '<a href="'.get_the_permalink( $post).'">'.$post->post_content.'</a>';
			}else{
				$res .= $post->post_content;
			}
		}
		if($content == 'main'){
			$result = get_extended($post->post_content) ;
			if ( in_array( 'main', $permalink_array ) ) {
				$res .= '<a href="' . get_the_permalink( $post ) . '">' . $result['main'] . '</a>';
			} else {
				$res .= $result['main'];
				if(!empty($result['extended'])) {
					$res .= ' <a href="' . get_the_permalink( $post ) . '">' . str_replace( " ", '&nbsp;', $args['more_text'] ) . '</a>';
				}
			}
		}
	}
	if($atts['permalink'] == 'all'){
		$res .= '</a>';
	}

	$res .= '</div>';
	$post = $the_post;
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