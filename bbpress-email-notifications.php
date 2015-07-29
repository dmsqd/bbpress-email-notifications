<?php
/*
Plugin Name: bbPress Email Notifications
Plugin URI: http://www.cornerstoneseducation.co.uk
Description: Emails the site administrator whenever a post is created or replied to in bbPress.
Version: 0.1
License: Copyright DMSQD
Author: DMSQD
Author URI: http://dmsqd.com
*/

add_action( 'bbp_new_topic', function( $topic_id, $forum_id, $anonymous_data, $topic_author ){
    $admin_email = get_option('admin_email');

    $forum_title = bbp_topic_forum_title($topic_id);

    $topic = bbp_get_topic($topic_id);
    $url = get_permalink($topic_id);

    $success = wp_mail( $admin_email,
        "New post in forum {$forum_title}",
        "A new post has been created in the forum {$forum_title}.
        Please visit the following URL to view it: <a href=\"{$url}\">{$url}</a>"
    );

}, 10, 5);

add_action( 'bbp_new_reply', function( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author ){

    $admin_email = get_option('admin_email');

    $url = get_permalink($reply_id);

    wp_mail( $admin_email,
        "New reply to a post in forum {$forum_title}",
        "A new reply has been posted to a post in the forum {$forum_title}.
        Please visit the following URL to view it: <a href=\"{$url}\">{$url}</a>"
    );
}, 10, 6 );

// prevent checking for updates in this plugin
add_filter( 'http_request_args', function( $r, $url ) {
    if ( -1 == strpos( $url, '//api.wordpress.org/plugins/update-check' ) ) {
        return $r; // Not a plugin update request. Bail immediately.
    }

    $plugins = json_decode( $r['body']['plugins'] );
    if ( $plugins ) {
        unset( $plugins->plugins->{plugin_basename( __FILE__ )} );
        unset( $plugins->active->{plugin_basename( __FILE__ )} );
        $r['body']['plugins'] = json_encode( $plugins );
    }
    return $r;
}, 5, 2 );
