<?php
// Add custom Theme Functions here
add_filter('widget_text','execute_php',100);
function execute_php($html){
     if(strpos($html,"<"."?php")!==false){
          ob_start();
          eval("?".">".$html);
          $html=ob_get_contents();
          ob_end_clean();
     }
     return $html;
}
// Code đếm số dòng trong văn bản
function count_paragraph( $insertion, $paragraph_id, $content ) {
        $closing_p = '</p>';
        $paragraphs = explode( $closing_p, $content );
        foreach ($paragraphs as $index => $paragraph) {
                if ( trim( $paragraph ) ) {
                        $paragraphs[$index] .= $closing_p;
                }
                if ( $paragraph_id == $index + 1 ) {
                        $paragraphs[$index] .= $insertion;
                }
        }
 
        return implode( '', $paragraphs );
}
//Chèn bài liên quan vào giữa nội dung
 
add_filter( 'the_content', 'prefix_insert_post_ads' );
 
function prefix_insert_post_ads( $content ) {
 
        $related_posts= "<div class='meta-related'>".do_shortcode('[related_posts_by_tax title=""]')."</div>";
 
        if ( is_single() ) {
                return count_paragraph( $related_posts, 1, $content );
        }
 
        return $content;
}
function the_breadcrumb() {
                echo '<ul id="crumbs">';
        if (!is_home()) {
                echo '<li><a href="';
                echo get_option('home');
                echo '">';
                echo 'Trang chủ';
                echo "</a> › </li>";
                if (is_category() || is_single()) {
                        echo '<li>';
                        the_category('</li> <li> ');
                        if (is_single()) {
                                echo " ›  </li> <li> ";
                                the_title();
                                echo ' </li>';
                        }
                } elseif (is_page()) {
                        echo ' <li>';
                        echo the_title();
                        echo ' </li>';
                }
        }
        elseif (is_tag()) {single_tag_title();}
        elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
        elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
        elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
        elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
        elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
        elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
        echo '</ul>';
}
//CODE LAY LUOT XEM
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "01 lượt xem";
    }
    return $count.' lượt xem';
}
 
// CODE DEM LUOT XEM
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
  
//CODE HIEN THI SO LUOT XEM BAI VIET TRONG DASHBOARDH
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
    if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}
function smartline_meta_date_capnhat() {
 
	$time_string = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date published updated" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
 
	echo '<span class="meta-date-capnhat">' . $time_string . '</span>';
}