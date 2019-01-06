<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;
use Inc\Api\AdminPageCallbacks;
/**
*
*/
class AdminPages extends BaseController{
	public $settings;
	public $callbacks;
	public $pages = array();
	public $subpages = array();

	public function register(){
		$this->settings = new SettingsApi();
		$this->callbacks = new AdminPageCallbacks();

		$this->setPages();
		$this->setSubpages();

		//set costum fields
		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();

		$this->settings->addSubPages( $this->subpages )->register();
	}

	public function setPages(){
		$this->pages = array(
			array(
				'page_title' => 'morrigan Plugin',
				'menu_title' => 'Morrigan Plugin',
				'capability' => 'manage_options',
				'menu_slug' => 'morrigan_plugin',
				'callback' => array( $this->callbacks, 'morriganAdminDashboard' ),
				'icon_url' => '',
				'position' => 110
			)
		);
	}
	public function setSubpages(){
		$this->subpages = array(
			array(
				'parent_slug' => 'morrigan_plugin',
				'page_title' => 'Slider',
				'menu_title' => 'Slider',
				'capability' => 'manage_options',
				'menu_slug' => 'edit.php?post_type=slider'
			),
			array(
				'parent_slug' => 'morrigan_plugin',
				'page_title' => 'Questions',
				'menu_title' => 'Questions',
				'capability' => 'manage_options',
				'menu_slug' => 'edit.php?post_type=questions'
			)
		);
	}
	public function setSettings(){
		$args = array(
			array(
				'option_group' => 'morrigan_options_group',
				'option_name' => 'text_example',
				'callback' => array( $this->callbacks, 'morriganOptionsGroup' )
			)
		);
		$this->settings->setSettings( $args );
	}
	public function setSections(){
		$args = array(
			array(
				'id' => 'morrigan_admin_index',
				'title' => 'Settings',
				'callback' => array( $this->callbacks, 'morriganAdminSection' ),
				'page' => 'morrigan_plugin'
			)
		);
		$this->settings->setSections( $args );
	}
	public function setFields(){
		$args = array(
			array(
				'id' => 'text_example',
				'title' => 'Text Example',
				'callback' => array( $this->callbacks, 'morriganTextExample' ),
				'page' => 'morrigan_plugin',
				'section' => 'morrigan_admin_index',
				'args' => array(
					'label_for' => 'text_example',
					'class' => 'example-class'
				)
			)
		);
		$this->settings->setFields( $args );
	}
}
