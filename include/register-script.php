<?php

// Plugin Included File
function projects_admin_scripts(){
	wp_enqueue_style( 'switchery-css', plugins_url( '../admin/switchery/switchery.css', __FILE__ ) );
	wp_enqueue_script( 'switchery-js', plugins_url( '../admin/switchery/switchery.js', __FILE__ ) );
	wp_enqueue_script( 'admin-js', plugins_url( '../admin/admin.js', __FILE__ ) );
	wp_enqueue_style( 'admin-css', plugins_url( '../admin/admin.css', __FILE__ ) );
}
add_action( 'admin_init', 'projects_admin_scripts' );

// Theme Style
function projects_theme_style() {	
	wp_enqueue_style( 'theme-style', plugins_url( '../css/theme-style.css', __FILE__ ) );
	wp_enqueue_style( 'font-awesome', plugins_url( '../css/font-awesome.min.css', __FILE__ ) );
	
	$projects_gallery_type = get_option('projects_gallery_type');
	if($projects_gallery_type == 1){
		wp_enqueue_style( 'magnific-popup', plugins_url( '../css/magnific-popup.css', __FILE__ ) );
	} elseif($projects_gallery_type == 2) {
		wp_enqueue_style( 'owl-carousel', plugins_url( '../css/owl.carousel.css', __FILE__ ) );
	}
}
add_action( 'wp_enqueue_scripts', 'projects_theme_style', 20 );

// Add style to theme head
function projects_add_stylesheet_to_head() {
	?>
    <style type="text/css">
		
	</style>
	<?php
}
add_action('wp_head', 'projects_add_stylesheet_to_head');

// Add script to theme footer
function projects_add_js_to_footer() {
	wp_enqueue_script( 'filterable', plugins_url( '../js/filterable.js', __FILE__ ) );
	?>
	<script>  
        jQuery(document).ready(function() {   
            jQuery(".project-list").filterable();  
        });  
    </script>
    <?php 
	$projects_gallery_type = get_option('projects_gallery_type');
	if($projects_gallery_type == 1){
		wp_enqueue_script( 'swipebox', plugins_url( '../js/jquery.magnific-popup.js', __FILE__ ) );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.project-gallery-item').magnificPopup({
					delegate: 'a',
					type: 'image',
					tLoading: 'در حال بارگذاری تصویر #%curr%...',
					mainClass: 'mfp-img-mobile',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1] // Will preload 0 - before current, and 1 after the current image
					},
					zoom: {
						enabled: true,
						duration: 300, // don't foget to change the duration also in CSS
						opener: function(element) {
							return element.find('img');
						}
					}
				});
			});
		</script>
		<?php
	} elseif($projects_gallery_type == 2) {
		wp_enqueue_script( 'owl-carousel', plugins_url( '../js/owl.carousel.js', __FILE__ ) );
		?>
		<script type="text/javascript">
			jQuery(function() {	
				var $sync1 = jQuery("#project-gallery-slideshow"),
					$sync2 = jQuery("#project-gallery-slideshow-thumbnail"),
					flag = false,
					duration = 300;
			
				$sync1.owlCarousel({
					nav : false,
					items : 1,
					margin: 10,
					autoplay:true,
					rtl:true,
					autoHeight:true,
				})
				.on('changed.owl.carousel', function (e) {
					if (!flag) {
						flag = true;
						$sync2.trigger('to.owl.carousel', [e.item.index, duration, true]);
						flag = false;
					}
				});
			
				$sync2.owlCarousel({
					nav : false,
					items : 4,
					margin: 10,
					autoplay:true,
					rtl:true,
				})
				.on('click', '.owl-item', function () {
					$sync1.trigger('to.owl.carousel', [jQuery(this).index(), duration, true]);
				})
			});
		</script>
        <?php
	}
}
add_action('wp_footer', 'projects_add_js_to_footer');