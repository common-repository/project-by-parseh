<?php

add_action('wp_ajax_nopriv_post-like', 'projects_post_like');
add_action('wp_ajax_post-like', 'projects_post_like');

add_action( 'wp_enqueue_scripts', 'projects_like_system_script' );
function projects_like_system_script()
{	
	wp_enqueue_script('like_post', plugins_url( '/post-like.js', __FILE__ ), array('jquery'), '1.0', true );
	wp_localize_script('like_post', 'ajax_var', array(
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ajax-nonce')
	));
}

function projects_post_like()
{
    $nonce = $_POST['nonce'];
  
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');
     
    if(isset($_POST['post_like']))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];
         
        $meta_IP = get_post_meta($post_id, "_voted_IP");
        $voted_IP = $meta_IP[0];
 
        if(!is_array($voted_IP))
            $voted_IP = array();
         
        $meta_count = get_post_meta($post_id, "votes_count", true);
 
        if(!projects_hasAlreadyVoted($post_id))
        {
            $voted_IP[$ip] = time();
 
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "votes_count", ++$meta_count);
             
            echo $meta_count;
        }
        else
            echo "already";
    }
    exit;
}

$timebeforerevote = 525600;

function projects_hasAlreadyVoted($post_id)
{
    global $timebeforerevote;
 
    $meta_IP = get_post_meta($post_id, "voted_IP");
    $voted_IP = $meta_IP[0];
     
    if(!is_array($voted_IP))
        $voted_IP = array();
         
    $ip = $_SERVER['REMOTE_ADDR'];
     
    if(in_array($ip, array_keys($voted_IP)))
    {
        $time = $voted_IP[$ip];
        $now = time();
         
        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;
             
        return true;
    }
     
    return false;
}

function projects_getPostLikeLink($post_id)
{
 
    $vote_count = get_post_meta($post_id, "votes_count", true);
	if($vote_count==""){
		$vote_count = "0";	
	}
 
    $output = '<div class="post-like">';
    if(projects_hasAlreadyVoted($post_id))
        $output .= '<span title="شما این پروژه را لایک کردید" class="alreadyvoted"><i class="fa fa-heart"></i></span>';
    else
        $output .= '<a href="#" data-post_id="'.$post_id.'" title="این پروژه را لایک کنید"><i class="fa fa-heart"></i>';
    $output .= '<span class="count"> '.$vote_count.'</span></a></div>';
     
    return $output;
}