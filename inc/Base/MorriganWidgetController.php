<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Base;
use \Inc\Widgets\MorriganWidget;
use \Inc\Widgets\MorriganBoxes;
use \Inc\Widgets\MorriganHeader;
use \Inc\Widgets\MorriganContactForm;
use \Inc\Widgets\RecentPosts;
use \Inc\Widgets\AskedQuestions;
/**
*
*/
class MorriganWidgetController extends BaseController
{
	public function register()
	{
		$media_widget = new MorriganWidget();
		$media_widget->register();

		$boxes_widget = new MorriganBoxes();
		$boxes_widget->register();

		$header_widget = new MorriganHeader();
		$header_widget->register();

		$contactfrom_widget = new MorriganContactForm();
		$contactfrom_widget->register();

		$recentposts_widget = new RecentPosts();
		$recentposts_widget->register();

		$question_widget = new AskedQuestions();
		$question_widget->register();
	
	}
}
