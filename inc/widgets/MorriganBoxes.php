<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Widgets;
use WP_Widget;
use WP_Query;
/**
*
*/
class MorriganBoxes extends WP_Widget{

	public $widget_ID;
	public $widget_name;
	public $widget_options = array();
	public $control_options = array();

	function __construct() {
		$this->widget_ID = 'morrigan_menu_type_widget';
		$this->widget_name = 'Menu boxes';
		$this->widget_options = array(
			'classname' => $this->widget_ID,
			'description' =>__('A customizable media and text boxes. Can add flip animation.'),
			'customize_selective_refresh' => true,
		);
		$this->control_options = array(
			'width' => '100%',
			'height' => 350,
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}

	public function register(){

		parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );
		add_action( 'widgets_init', array( $this, 'widgetsInit' ) );
	}

	public function widgetsInit(){
		register_widget( $this );
	}

	public function widget( $args, $instance ) {

		// Classes changes
		$widgetSize1 = ( '1' == $instance['widget_size'] ) ? 'col-md-12' : (( '2' == $instance['widget_size'] ) ? 'col-md-6' : ( ( '3' == $instance['widget_size'] ) ? 'col-md-4' : ( ( '4' == $instance['widget_size'] ) ? 'col-md-3' : '' ) ) );

		// check if number is given!
		if ( ! $number = esc_attr( $instance['number'] ) ) $number = 5;
	// array to call recent posts
		if( ! $cats = $instance["cats"] )  $cats='';
		$rpw_args=array(
			'showposts' => $number,
			'category__in'=> $cats,
		);
		$r = null;
		$r = new WP_Query($rpw_args);

		// if ( 'on' == $instance['widget_effect'] ) {
		// 	$class1 .= ' border-bottom';
		// }

		extract( $args );
		?>
		<!-- Front view style -->
		<?php echo $before_widget; ?>
		<section>
			<div class="container-fluid row">
				<?php if ( ! empty( $instance['title'] ) ) { ?>
					<h1 class="col-12"><?php echo esc_attr( $instance['title'] ); ?></h1>
				<?php } ?>
				<!-- Start the loop -->
				<?php 	while ( $r->have_posts() )
					{
						$r->the_post();

						$widgetEffect = ((( 'on' == $instance['widget_effect'] ) && has_post_thumbnail($post_id)) ? 'widget-flip-effect' : 'widget-no-effect');
						// check if post has a post-thumbnail or not
						$imageurl = get_the_post_thumbnail_url( $post_id );
						$backgroundImage = (has_post_thumbnail($post_id)? $imageurl : '');
						// widget background color
						$WidgetBackgroundColor =
							(('on'==$instance['widget_effect']) && (has_post_thumbnail($post_id))) ? $instance['backgroundcolor']
							:(((''==$instance['widget_effect']) && ( ! has_post_thumbnail($post_id))) ? $instance['backgroundcolor']
							: ((('on' ==$instance['widget_effect'])&& ( ! has_post_thumbnail($post_id))) ?  $instance['backgroundcolor']
							: (((''==$instance['widget_effect']) && (has_post_thumbnail($post_id)))? 'rgba(0, 0, 0, 0.42)' :
							$instance['backgroundcolor'])));
					?>

					<div class="<?php echo esc_attr( $widgetSize1 ); ?>">

						<div data-aos="fade-down" data-aos-duration="1000" class="morrigan_widget_menu">

							<div class="<?php echo esc_attr( $widgetEffect); ?>">

									<div class="morigan-plugin-image morrigan-text-center" style="background-image:url(<?php echo esc_attr( $backgroundImage ); ?>)">
										<?php if( 'on' == $instance['widget_effect'] ){ ?>
											<i class="fas fa-2x fa-angle-left"></i>
										<?php } ?>
									</div>

								<div class="morrigan-plugin-text" style="background-color:<?php echo esc_attr( $WidgetBackgroundColor ); ?>; color:<?php echo esc_attr( $instance[ 'textcolor' ] ); ?> ">

									<div class="col-md-12">
											<h4><?php the_title(); ?></h4>
											<p><?php echo the_content(); ?></p>
									</div>

								</div>
							</div>
						</div>
					</div>

				<?php }
				 wp_reset_query();?> <!-- End the loop -->
			</div>

		</section>
	<?php echo $after_widget; ?>
		<?php

	}
 	// For back-end view (widget form creation)
	public function form( $instance ) {
			// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$backgroundcolor = esc_attr( $instance[ 'backgroundcolor' ] );
			$textcolor = esc_attr( $instance[ 'textcolor' ] );
			$number = esc_attr($instance['number']);
		} else {
			$title = '';
			$backgroundcolor = '#f62459';
			$textcolor = "#fff";
			$number = '5';
		};
		// images
		$two_column = plugins_url("morrigan-plugin/assets/images/two-column.png" );
		$three_column = plugins_url("morrigan-plugin/assets/images/three-column.png" );
		$four_column = plugins_url("morrigan-plugin/assets/images/four-column.png" );
		$profession = ! empty( $instance['profession'] ) ? $instance['profession'] : '';

		?>
	  <!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Widget Title' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<!-- Category  -->
		<p>
			 <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the recent posts list:');?>

					 <?php
							$categories=  get_categories('hide_empty=0');
								echo "<br/>";
								foreach ($categories as $cat) {
										$option='<input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
											 if (is_array($instance['cats'])) {
													 foreach ($instance['cats'] as $cats) {
															 if($cats==$cat->term_id) {
																		$option=$option.' checked="checked"';
															 }
													 }
											 }
											 $option .= ' value="'.$cat->term_id.'" />';
		 $option .= '&nbsp;';
											 $option .= $cat->cat_name;
											 $option .= '<br />';
											 echo $option;
										}

							 ?>
			 </label>
		 </p>
		 	<!-- Number of posts to show -->
		 <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of posts to show:' ); ?></label>
			 <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" size="3"  type="text" value="<?php echo esc_attr( $number ); ?>">
		</p>
			<!-- Widget Size -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_size' ) ); ?>">
				<?php echo esc_html__( 'How many in one row: '); ?>
			</label>
			<select class="image-size" name="<?php echo esc_attr( $this->get_field_name( 'widget_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( '' ) ); ?>">
				<option value="1" <?php selected( $instance['widget_size'], '1' ); ?>>
					<?php echo esc_html__( '1' ); ?>
				</option>
				<option value="2" <?php selected( $instance['widget_size'], '2' ); ?>>
					<?php echo esc_html__( '2' ); ?>
				</option>
				<option value="3" <?php selected( $instance['widget_size'], '3' ); ?>>
					<?php echo esc_html__( '3' ); ?>
				</option>
				<option value="4" <?php selected( $instance['widget_size'], '4' ); ?>>
					<?php echo esc_html__( '4' ); ?>
				</option>
			</select>
		</p>

		<!-- Hover effects on / off -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_effect' ) ); ?>">
				<?php echo esc_html__( 'Widget flip effect: '); ?>
			</label>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'widget_effect' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_effect' ) ); ?>" class="" value="on"
					<?php checked( $instance['widget_effect'], 'on' ); ?>>
				<label class="onoffswitch-label" for="<?php echo esc_attr( $this->get_field_name( 'widget_effect' ) ); ?>"></label>
		<p>
			<!-- Background color  -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'backgroundcolor' ) ); ?>"><?php echo esc_html__( 'Change default background color ' ); ?></label><br>
			   <input class="widefat color-picker" id="<?php echo $this->get_field_id( 'backgroundcolor' ); ?>" name="<?php echo $this->get_field_name( 'backgroundcolor' ); ?>"  data-default-color="#f62459" value="<?php echo $backgroundcolor; ?>" />
		</p>
		<!-- Text color  -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'textcolor' ) ); ?>"><?php echo esc_html__( 'Change default text Color ' ); ?></label>
				 <input class="widefat text-color-picker" id="<?php echo $this->get_field_id( 'textcolor' ); ?>" name="<?php echo $this->get_field_name( 'textcolor' ); ?>"  data-default-color="#fff" value="<?php echo $textcolor; ?>" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['widget_size'] = sanitize_text_field( $new_instance['widget_size'] );
		$instance['widget_effect'] = ( ! empty( $new_instance['widget_effect'] ) ) ? esc_html( $new_instance['widget_effect'] ) : '';
		$instance['backgroundcolor'] = strip_tags( $new_instance['backgroundcolor']);
		$instance['textcolor'] = strip_tags( $new_instance['textcolor']);
		$instance['cats'] = $new_instance['cats'];
		$instance['number'] = sanitize_text_field( $new_instance['number'] );
		return $instance;
	}
}
