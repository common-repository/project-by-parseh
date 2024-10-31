<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Projects ( Parseh Design Studio )
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 */

add_filter( 'cmb_meta_boxes', 'projects_post_metaboxes' );
function projects_post_metaboxes( array $meta_boxes ) {

	$prefix = 'projects_';

	$meta_boxes['test_metabox'] = array(
		'id'         => 'test_metabox',
		'title'      => __( 'جزییات پروژه', 'parseh-design' ),
		'pages'      => array( 'projects', ), 
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name'       => __( 'کارفرما', 'parseh-design' ),
				'desc'       => __( 'نام کارفرما را وارد کنید (اختیاری)', 'parseh-design' ),
				'id'         => $prefix . 'client',
				'type'       => 'text',
			),
			array(
				'name' => __( 'آدرس وبسایت', 'parseh-design' ),
				'desc' => __( 'آدرس وبسایت پروژه را وارد کنید (اختیاری)', 'parseh-design' ),
				'id'   => $prefix . 'url',
				'type' => 'text_url',
			),
			array(
				'name'    => __( 'توضیح کوتاه پروژه', 'parseh-design' ),
				'desc'    => __( 'در مورد پروژه چند خطی توضیح دهید (اختیاری)', 'parseh-design' ),
				'id'      => $prefix . 'description',
				'type'    => 'wysiwyg',
				'options' => array( 'textarea_rows' => 5, ),
			),
			array(
				'name'         => __( 'گالری تصاویر پروژه', 'parseh-design' ),
				'desc'         => __( 'یک یا چند تصویر برای پروژه انتخاب کنید', 'parseh-design' ),
				'id'           => $prefix . 'gallery',
				'type'         => 'file_list',
				'preview_size' => array( 150, 150 ),
			),
			array(
				'name' => __( 'تاریخ اجرای پروژه', 'parseh-design' ),
				'desc' => __( 'تاریخ اجرای پروژه (اختیاری)', 'parseh-design' ),
				'id'   => $prefix . 'date',
				'type' => 'text_date',
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'projects_initialize_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function projects_initialize_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
