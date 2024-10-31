<?php 

/* 
Plugin Name: Project by Parseh
Plugin URI: http://parseads.com/wordpress-plugin/project-by-parseh
Description: این افزونه به شما کمک می کند که به راحتی پروژه های انجام شده خودتان را با استفاده از Shortcode نمایش دهید
Version: 1.0.1 
Author: استودیو طراحی پارسه
Author URI: http://parseads.com/
License: GPL2
*/ 

/* 
Project included file
Since : Version 1.0.0
*/

// Project Include File
include_once('include/register-script.php');

// Project Metabox
require_once("metabox/metabox-functions.php");

// Project Like
include_once( 'include/like.php' );

/* 
Project add custom image size
Since : Version 1.0.0
*/
add_image_size( 'project', 300, 300, true );

/* 
Project activation hook
set option default
Since : Version 1.0.0
*/
function projects_activation() {
	$projects_column = get_option('projects_column');
	if(!$projects_column){
		update_option('projects_column','3');	
	}
	
	$projects_filter = get_option('projects_filter');
	if(!$projects_filter){
		update_option('projects_filter','1');	
	}
	
	$projects_gallery_type = get_option('projects_gallery_type');
	if(!$projects_gallery_type){
		update_option('projects_gallery_type','1');
	}
	
	$projects_website = get_option('projects_website');
	if(!$projects_website){
		update_option('projects_website','1');
	}
	
	$projects_client = get_option('projects_client');
	if(!$projects_client){
		update_option('projects_client','1');
	}
	
	$projects_like = get_option('projects_like');
	if(!$projects_like){
		update_option('projects_like','1');
	}
	
	$projects_date = get_option('projects_date');
	if(!$projects_date){
		update_option('projects_date','1');
	}
	
	$projects_view = get_option('projects_view');
	if(!$projects_view){
		update_option('projects_view','1');
	}	
}
register_activation_hook( __FILE__, 'projects_activation' );

/* 
Project add page submenu
Since : Version 1.0.0
*/
function projects_add_admin_menus() {
	add_submenu_page( 'edit.php?post_type=projects', __( 'تنظیمات', 'parseh-design' ), __( 'تنظیمات', 'parseh-design' ), 'publish_posts', 'projects-settings', 'projects_settings_page' );
}
add_action( 'admin_menu', 'projects_add_admin_menus');

function projects_settings_page() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.', 'parseh-design'));
	}

	include_once('setting.php');
}

/* 
Project textdomain
Since : Version 1.0.0
*/
load_theme_textdomain('parseh-design', plugins_url( '/languages', __FILE__ ) );

/* 
Project add cutom post type
Since : Version 1.0.0
*/
function projects_init_function() {
	$lable = array(
		'name'               => __('پروژه ها','parseh-design'),
		'singular_name'      => __('پروژه','parseh-design'),
		'menu_name'          => __('پروژه ها','parseh-design'),
		'name_admin_bar'     => __('پروژه','parseh-design'),
		'add_new'            => __('افزودن پروژه جدید','parseh-design'),
		'add_new_item'       => __('افزودن پروژه جدید','parseh-design'),
		'new_item'           => __('افزودن پروژه جدید','parseh-design'),
		'edit_item'          => __('ویرایش پروژه','parseh-design'),
		'view_item'          => __('مشاهده پروژه','parseh-design'),
		'all_items'          => __('همه پروژه ها','parseh-design'),
		'search_items'       => __('جستجوی پروژه','parseh-design'), 
		'parent_item_colon'  => __('پروژه مادر :','parseh-design'),
		'not_found'          => __('پروژه یافت نشد','parseh-design'),
		'not_found_in_trash' => __('هیچ پروژه ای در سطل بازیافت پیدا نشد','parseh-design'),
	);
	
	register_post_type('projects', array(
		'labels' => $lable,
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array('slug' => 'projects'),
		'query_var' => true,
		'menu_position' => 4,
		'menu_icon' => 'dashicons-portfolio',
		'supports' => array(
			'title',
			'thumbnail',
			'editor',
			'author',
			'comments',
		)
	) );
	
	// Project Category
	register_taxonomy(
		'projects_category',
		'projects',
		array(
			'label' => 'دسته بندی',
			'rewrite' => array( 'slug' => 'projects-category' ),
			'hierarchical' => true,
		)
	);
}
add_action('init', 'projects_init_function');
 
/* 
Change Project title 
Since : Version 1.0.0
*/
function projects_enter_title( $input ) {
    global $post_type;
    if ( is_admin() && 'projects' == $post_type )
	return __( 'عنوان پروژه انجام شده', 'parseh-design' );
    return $input;
}
add_filter( 'enter_title_here', 'projects_enter_title' );

/* 
Flush Rewrite
Since : Version 1.0.0
*/
add_action('init', 'projects_custom_taxonomy_flush_rewrite');
function projects_custom_taxonomy_flush_rewrite() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

/* 
Add Post Class
Since : Version 1.0.0
*/
function projects_post_class( $class = '' ) {
	if(is_singular('projects'))
	$class[] = 'project-post-class';
	return $class;
	}
add_filter('post_class', 'projects_post_class');

/* 
Add detail to content
Since : Version 1.0.0
*/
function projects_single_add_custom_field( $content ) {
	global $post,$post_type;
	if(is_single() && 'projects' == $post_type){
		
		// Reterive Plugin Options
		$projects_gallery_type = get_option('projects_gallery_type');
		$projects_like_option = get_option('projects_like');
		$projects_date_option = get_option('projects_date');
		$projects_view_option = get_option('projects_view');
		$projects_website_option = get_option('projects_website');
		$projects_client_option = get_option('projects_client');
		
		// Reterive Projects Detail
		$client = get_post_meta($post->ID, 'projects_client', true);
		$website = get_post_meta($post->ID, 'projects_url', true);
		$description = get_post_meta($post->ID, 'projects_description', true);
		$gallery = get_post_meta($post->ID, 'projects_gallery', true);
		$date = get_post_meta($post->ID, 'projects_date', true);
		
		// Set Post View Counter
		projects_setPostViews(get_the_ID());
		
		$content = '<div class="project project-detail">';
			if($description){
				$content .= '<div class="project-description">'.$description.'</div>'; 
			}
			$content .= '<div class="grid">';
			$content .= '<div class="col-1-3">';
				if($projects_website_option && $website){
					$content .= '<div class="project-website"><a target="_blank" href="'.$website.'" title="مشاهده وبسایت"><i class="fa fa-link"></i> آدرس وبسایت</a></div>';
				}
				
				if($projects_like_option){
					$content .= '<div class="project-like">'.projects_getPostLikeLink($post->ID).'</div>';
				}
				
				if($projects_client_option && $client){
					$content .= '<div class="project-detail-item project-client"><i class="fa fa-user"></i> کارفرما : '.$client.'</div>';
				}
				
				if($projects_date_option && $date){
					$content .= '<div class="project-detail-item project-date"><i class="fa fa-calendar-check-o"></i> اجرای پروژه : '.$date.'</div>';
				}
				
				if($projects_view_option){
					$content .= '<div class="project-detail-item project-view"><i class="fa fa-eye"></i> بازدید : '.projects_getPostViews(get_the_ID()).'</div>';
				}
			$content .= '</div>';
			$content .= '<div class="col-2-3">';
				if($projects_gallery_type==1){
					$content .= '<div class="grid project-gallery">';
					if($gallery){
						foreach($gallery as $gallery_item) {
							$image_id = projects_get_attachment_id_by_url( $gallery_item );
							$image = wp_get_attachment_image_src( $image_id, 'thumbnail');
							$image_large = wp_get_attachment_image_src( $image_id, 'full');
							$content .= '<div class="col-1-3 project-gallery-item"><a rel="project-gallery" href="'.$image_large[0].'"  class="swipebox"><img src="'.$image[0].'"></a></div>';
						}
					}
					$content .= '</div>';
				} else {
					$content .= '<div id="project-gallery-slideshow" class="owl-carousel owl-theme">';
					if($gallery){
						foreach($gallery as $gallery_item) {
							$image_id = projects_get_attachment_id_by_url( $gallery_item );
							$image_large = wp_get_attachment_image_src( $image_id, 'full');
							$content .= '<div class="project-gallery-slideshow-item"><img src="'.$image_large[0].'"></div>';
						}
					}
					$content .= '</div>';
					$content .= '<div id="project-gallery-slideshow-thumbnail" class="owl-carousel owl-theme">';
					if($gallery){
						foreach($gallery as $gallery_item) {
							$image_id = projects_get_attachment_id_by_url( $gallery_item );
							$image = wp_get_attachment_image_src( $image_id, 'thumbnail');
							$content .= '<div class="project-gallery-slideshow-item"><img src="'.$image[0].'"></div>';
						}
					}	
					$content .= '</div>';	
				}
			$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="project-content">'.get_the_content().'</div>';
		$content .= '</div>';
		return $content;
	} else {
		$content = get_the_content();
		return $content;	
	}

}
add_filter('the_content','projects_single_add_custom_field');

/* 
Get Attachment ID
Since : Version 1.0.0
*/
function projects_get_attachment_id_by_url( $url ) {
	$parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
	if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
	return $attachment[0];
}

/* 
Project List
Since : Version 1.0.0
*/
function projects_list() {
	$column = get_option('projects_column');
    $options = array(
        'post_type' => 'projects',
    );
	
    $query = new WP_Query( $options );
	
    if ( $query->have_posts() ) { ?>
    
        <?php  
            $terms = get_terms("projects_category");  
            $count = count($terms); 
			$projects_filter = get_option('projects_filter');
			if($projects_filter==1){
				echo '<ul id="project-filter" class="project-filter project">';  
				echo '<li>'.__('دسته بندی : ','parseh-design').'</li><li><a href="#all" title="'.__('همه نمونه کارها','parseh-design').'">'.__('همه','parseh-design').'</a></li>';  
					if ( $count > 0 )  
					{     
						foreach ( $terms as $term ) {  
							$termname = strtolower($term->name);  
							$termname = str_replace(' ', '-', $termname);  
							echo '<li><a href="#'.$termname.'" title="'.__('نمونه کارهای','parseh-design').' '.$term->name.'" rel="'.$termname.'">'.$term->name.'</a></li>';  
						}  
					}  
				echo "</ul>";  
			}
        ?> 
         
        <div class="grid project-list project">
                        
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

		<?php  
        $terms = get_the_terms( $post->ID, 'projects_category' );  
                              
        if ( $terms && ! is_wp_error( $terms ) ) :   
            $links = array();  

            foreach ( $terms as $term )   
            {  
                $links[] = $term->name;  
            }  
            $links = str_replace(' ', '-', $links);   
            $tax = join( " ", $links );       
        else :    
            $tax = '';    
        endif;  
        ?>
                                    
        <div class="col-1-<?php echo $column ?> <?php echo strtolower($tax); ?> all">
        	<div class="projects-item">
            	<div class="projects-item-image">
                	<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                        <div class="project-item-overlay">
                        	<div class="project-item-overlay-link">
								<i class="fa fa-link"></i>
                            </div>
                        </div> 
                		<?php the_post_thumbnail('project', array( 'alt' => get_the_title())); ?>
                    </a>
                </div>
        		<div class="projects-item-title"><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></div>
            </div>
        </div>
        
        <?php endwhile;?>
        </div>
        <?php wp_reset_postdata();
	}
}

/* 
Project List Shortcode
Since : Version 1.0.0
*/
add_shortcode( 'projects', 'projects_list' );

/* 
Project Post View
Since : Version 1.0.0
*/
function projects_getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
	}
	return $count.'';
}

function projects_setPostViews($postID) {
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

/* 
Project add custom column
Since : Version 1.0.0
*/
add_filter('manage_projects_posts_columns', 'projects_posts_column_views');
add_action('manage_projects_posts_custom_column', 'projects_posts_custom_column_views',5,2);

function projects_posts_column_views($defaults){
	$defaults['projects_client'] = 'کارفرما';
	$defaults['projects_date'] = 'تاریخ اجرا پروژه';
	$defaults['projects_website'] = 'وبسایت';
	$defaults['projects_like'] = 'لایک';
	$defaults['projects_views'] = 'بازدید';
	return $defaults;
}

function projects_posts_custom_column_views($column_name, $id){
	$client = get_post_meta($id, 'projects_client', true);
	$website = get_post_meta($id, 'projects_url', true);
	$date = get_post_meta($id, 'projects_date', true);
	
	if($column_name === 'projects_views'){
		echo projects_getPostViews(get_the_ID());
	}
	if($column_name === 'projects_like'){
		echo projects_getPostLikeLink(get_the_ID());
	}
	if($column_name === 'projects_client'){
		echo $client;
	}
	if($column_name === 'projects_date'){
		echo $date;
	}
	if($column_name === 'projects_website'){
		if($website){
			echo '<a href="'.$website.'" title="مشاهده وبسایت پروژه"><span class="dashicons dashicons-admin-links"></span></a>';
		}
	}
}

/* 
Project add custom widget
Since : Version 1.0.0
*/
add_action( 'widgets_init', create_function('', 'return register_widget("ProjectWidget");') );
class ProjectWidget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'project_widget',
			__( 'آخرین پروژه ها', 'parseh-design' ),
			array( 'description' => __( 'نمایش لیست آخرین پروژه های انجام شده', 'parseh-design' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $post;
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		$options = array(
			'post_type' => 'projects',
			'posts_per_page' => $instance['count']
		);
		$query = new WP_Query( $options );
		if ( $query->have_posts() ) { ?>
			<div class="project-widget-list project">
            <div class="clear"></div>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="project-widget-list-item">
            	<?php if($instance['show_image']){ ?>
                	<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
					<?php the_post_thumbnail('thumbnail'); ?>
                    </a>
				<?php } ?>
                
				<a class="project-widget-title" href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a><br>
                
                <?php if($instance['show_like']){
					$vote_count = get_post_meta($post->ID, "votes_count", true);
					if($vote_count==""){
						$vote_count = "0";	
					}
                	echo '<span><i class="fa fa-heart"></i>'.$vote_count.' لایک</span>';
                } ?>
                
                <?php if($instance['show_view']){
					echo '<span><i class="fa fa-eye"></i>'.projects_getPostViews(get_the_ID()).' بازدید</span>';
                } ?>
			</div>
            <div class="clear"></div>
			<?php endwhile;?>
			</div>
			<?php wp_reset_postdata();
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'آخرین نمونه کارها', 'parseh-design' );
		$count = ! empty( $instance['count'] ) ? $instance['count'] : __( '5', 'parseh-design' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'عنوان ابزارک:','parseh-design' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'تعداد پروژه ها:','parseh-design' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $count ); ?>">
		</p>
        
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_image'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_image' ); ?>">نمایش تصویر پروژه</label>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_like'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_like' ); ?>" name="<?php echo $this->get_field_name( 'show_like' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_like' ); ?>">نمایش لایک</label>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_view'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_view' ); ?>" name="<?php echo $this->get_field_name( 'show_view' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_view' ); ?>">نمایش بازدید</label>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		$instance['show_image'] = $new_instance['show_image'];
		$instance['show_like'] = $new_instance['show_like'];
		$instance['show_view'] = $new_instance['show_view'];
		return $instance;
	}

}


