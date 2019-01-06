<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Pages\AdminPages;
use Inc\Api\AdminPageCallbacks;
/**
*
*/
// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

class CostumPostTypeController extends BaseController{


	public function register(){
		$this->callbacks = new AdminPageCallbacks();

		add_action( 'init',  array( $this, 'create_question_post_type' ) );

		add_action( 'init',  array( $this, 'create_slider_post_type' ) );
	}

	public function create_question_post_type(){
		register_post_type( 'questions',
			array(
				'labels' => array(
					'name' => __( 'Questions' ),
					'singular_name' => __( 'Question' )
				),
				'public' => true,
				'has_archive' => true,
				'show_in_menu' => '',
				'supports' => array('title','editor')
			)
		);
	}
	public function create_slider_post_type(){
		$labels = array(
				'name'              => _x( 'Slider', 'post type general name' ),
				'singular_name'     => _x( 'Slider', 'post type singular name' ),
				'add_new'           => __( 'Add New Slide' ),
				'add_new_item'      => __( 'Add New Slide' ),
				'edit_item'         => __( 'Edit Slider' ),
				'new_item'          => __( 'New Slide' ),
				'view_item'         => __( 'View Slide' ),
				'search_items'      => __( 'Search Slides' ),
				'not_found'         => __( 'Slide' ),
				'not_found_in_trash'=> __( 'Slide' ),
				'parent_item_colon' => __( 'Slide' ),
				'menu_name'         => __( 'Slider' )
		);

		$taxonomies = array();

		$supports = array('title','thumbnail','editor');
		$post_type_args = array(
				'labels'            => $labels,
				'singular_label'    => __('Slide'),
				'public'            => true,
				'show_ui'           => true,
				'publicly_queryable'=> true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'capability_type'   => 'post',
				'has_archive'       => false,
				'hierarchical'      => false,
				'show_in_menu'      => '',
				'rewrite'           => array( 'slug' => 'slider', 'with_front' => false ),
				'supports'          => $supports,
				'menu_position'     => 27, // Where it is in the menu. Change to 6 and it's below posts. 11 and it's below media, etc.
				'menu_icon'         => 'dashicons-store',
				'taxonomies'        => $taxonomies
		);
		register_post_type('slider',$post_type_args);
	}
}
