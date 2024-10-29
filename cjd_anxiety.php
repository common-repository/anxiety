<?php
/*
Plugin Name: Seperation Anxiety
Plugin URI: http://chrisjdavis.org/category/wp-hacks
Version: 1.2
Description: Seperate those tracks and pings.
Author: Chris J. Davis, Scott Merill
Author URI: http://chrisjdavis.org
*/

//place this where you want the pings and tracks to be displayed.

function cjd_ping_track() {
global $post, $wpdb, $id, $comment;
$ping_track = mysql_query("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND comment_type !='' ORDER BY comment_date");
	while ( $row = mysql_fetch_array($ping_track) ) {
			$author = $row["comment_author"];
			$url = $row["comment_author_url"];
			$type = $row["comment_type"];
			$date = $row["comment_date"];
				echo "<li>";
				echo $type;
				echo " from ";
				echo '<a href="';
				echo $url;
				echo '">';
				echo $author;
				echo "</a>";
				echo "<br />";
				echo " on ";
				echo mysql2date(get_settings('date_format'),$date);
				echo "</li>";
    	} 
	}

//replace the comments_template function call with this in your index.php.

function cjd_comments_template() {
	global $withcomments, $post, $wpdb, $id, $comment;

	if ( is_single() || is_page() || $withcomments ) :
		$req = get_settings('require_name_email');
        $comment_author = isset($_COOKIE['comment_author_'.COOKIEHASH]) ? trim(stripslashes($_COOKIE['comment_author_'.COOKIEHASH])) : '';
		$comment_author_email = isset($_COOKIE['comment_author_email_'.COOKIEHASH]) ? trim(stripslashes($_COOKIE['comment_author_email_'.COOKIEHASH])) : '';
		$comment_author_url = isset($_COOKIE['comment_author_url_'.COOKIEHASH]) ? trim(stripslashes($_COOKIE['comment_author_url_'.COOKIEHASH])) : '';
		$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND comment_type= '' ORDER BY comment_date");

		$template = get_template_directory();
		$template .= "/comments.php";

		if (file_exists($template)) {
			include($template);
		}	else {
			include(ABSPATH . 'wp-comments.php');
		}

	endif;
}

//Use this to display how many tracks and pings you currently have.
// Updated with some help from Skippy (Scott Merill) skippy dot net
function get_pingback_count($post_id) {
global $wpdb;
$ping_count = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_approved = '1' AND comment_type == 'pingback'");
return $ping_count;}function get_trackback_count($post_id = 0) {
global $wpdb;
$track_count = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_approved = '1' AND comment_type == 'trackback'");
return $track_count;}function get_total_count($post_id) {
global $wpdb;
$total_count = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_approved = '1' AND comment_type != ''");
	return $total_count;}

function trackback_count($post_id) {
	echo get_trackback_count($post_id);}

function pingback_count($post_id) {
	echo get_pingback_count($post_id);}

function total_count($post_id) {
	echo get_total_count($post_id);}?>