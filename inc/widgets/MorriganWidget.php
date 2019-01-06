<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Widgets;
use WP_Widget;
/**
*
*/
class MorriganWidget extends WP_Widget{

	public $widget_ID;
	public $widget_name;
	public $widget_options = array();
	public $control_options = array();

	function __construct() {
		$this->widget_ID = 'morrigan_media_widget';
		$this->widget_name = 'Morrigan Media Widget';
		$this->widget_options = array(
			'classname' => $this->widget_ID,
			'description' => $this->widget_name,
			'customize_selective_refresh' => true,
		);
		$this->control_options = array(
			'width' => 400,
			'height' => 350,
		);
	}

	public function register(){

		parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );
		add_action( 'widgets_init', array( $this, 'widgetsInit' ) );
	}

	public function widgetsInit(){
		register_widget( $this );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$class1 = (empty( $instance['image'] ) ? 'full-width-widget' : 'col-md-12');
		$class2 = (empty( $instance['image'] ) ? 'full-width col-md-12' : 'col-md-6');
		$class3 = (empty( $instance['image'] ) ? '' : '');
		?>
		<section>
			<div  data-aos="fade-down" data-aos-duration="1000" class="<?php echo esc_attr( $class1 ); ?>">

				<div class="row morrigan-widget-content ">
					<?php
						// starts if image positison is on left right
					if ( selected( $instance['image_pos'], 'right', false ) ) { ?>
					<div class="<?php echo esc_attr( $class2 ); ?> morrigan-text-center ">
						<div class="widget-text">
							<?php
								if(empty( $instance['image'] )):?>
								<h2><i class="fas fa-quote-right"></i></h2>
							<?php  endif; ?>
								<?php if ( ! empty( $instance['title'] ) ) { ?>

									<h2 class="widget-tittle"><?php echo esc_attr( $instance['title'] ); ?></h2>

								<?php } ?>
							<p class="widget-text-content">
 							 <?php
 							 if ( ! empty( $instance['body_content'] ) ) {
 								 echo $args['before_content'] . apply_filters( 'widget_content', $instance['body_content'] ) . $args['after_content'];
 							 }
 								?>
 						 <p>
 						 <?php
 							 if ( ! empty( $instance['button_href']) && ! empty($instance['button_name']) ) { ?>
 								 <a id="<?php  echo esc_attr( $this->get_field_id( 'button_name' ) );?>" class="btn morriga-media-button" href="<?php echo esc_attr( $instance['button_href'] ); ?>"><?php echo esc_attr( $instance['button_name'] ); ?> <i class="fas fa-chevron-right"></i></a>
 						<?php }?>
						</div>

					</div>
						<?php
							if ( ! empty( $instance['image'] ) ) {?>
						<div class="col-md-6 heigh500">
							<div class="image-outer-div">
								<div class=" morigan-plugin-image morrigan-text-center" style="background-image:url(<?php echo esc_attr( $instance['image'] ); ?>)">
								</div>
							</div>
						</div>
					<?php } ?>
				<?php }
				// ends if image position is on right side
				?>

				<?php
					// starts if image positison is on left side
				if (selected( $instance['image_pos'], 'left', false ) ) { ?>
					<?php
						if ( ! empty( $instance['image'] ) ) {?>
							<div class="col-md-6 heigh500">
								<div class="image-outer-div">
									<div class=" morigan-plugin-image morrigan-text-center" style="background-image:url(<?php echo esc_attr( $instance['image'] ); ?>)">
									</div>
								</div>
							</div>
				<?php } ?>
				<div class="<?php echo esc_attr( $class2 ); ?> morrigan-text-center">
					<div class="widget-text">
							<?php
								if(empty( $instance['image'] )):?>
									<h2><i class="fas fa-quote-right"></i></h2>
							<?php  endif; ?>
							<?php if ( ! empty( $instance['title'] ) ) { ?>

								<h2 class="widget-tittle"><?php echo esc_attr( $instance['title'] ); ?></h2>

							<?php } ?>
						<p class="widget-text-content">
						 <?php
						 if ( ! empty( $instance['body_content'] ) ) {

							 echo esc_attr( $instance['body_content'] );
						 }
							?>
					 <p>
					 <?php
						 if ( ! empty( $instance['button_href']) && ! empty($instance['button_name']) ) { ?>
							 <a id="<?php  echo esc_attr( $this->get_field_id( 'button_name' ) );?>" class="btn morriga-media-button" href="<?php echo esc_attr( $instance['button_href'] ); ?>"><?php echo esc_attr( $instance['button_name'] ); ?> <i class="fas fa-chevron-right"></i></a>
					<?php }?>
					</div>

				</div>
			<?php } ?>
				</div>
			</div>
		</section>
		<?php
		echo $args['after_widget'];
	}
 	// For back-end view
	public function form( $instance ) {
    $placeholder_url = plugins_url("morrigan-plugin/assets/placeholder-image.jpg" );
		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$body_content = $instance['body_content'];
			$image = $instance['image'];
			$button_name = esc_attr( $instance[ 'button_name' ] );
			$button_href = esc_attr( $instance[ 'button_href' ] );
		} else {
			$title = '';
			$body_content = '';
			$image = '';
			$button_name = "";
			$button_href = "";
		};

		?>
		  <!-- Title -->
		<p>
  		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
  		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		  <!-- Description -->
    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'body_content' ) ); ?>">
			Description: </label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('body_content'); ?>" name="<?php echo $this->get_field_name('body_content'); ?>" rows="7" cols="20" ><?php echo $body_content; ?></textarea>
    </p>
		  <!-- BUtton URL -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_href' ) ); ?>">
				<?php echo esc_html__( 'Button URL: ' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_href' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_href' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['button_href'] ); ?>">
  		<!-- Text on button -->
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_name' ) ); ?>">
				<?php echo esc_html__( 'Text on button: ' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['button_name'] ); ?>">
		</p>
		  <!-- Select Image -->
    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_attr_e( 'Image:', 'awps' ); ?></label>

      <img style="height:auto;max-width: 100%;" data-default="<?php echo $placeholder_url; ?>" src="<?php echo '' != $instance['image'] ? esc_url( $instance['image'] ) : $placeholder_url; ?>" />

      <input class="widefat image-upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_url( $image ); ?>">

			<button type="button" class="button button-primary js-image-upload">Select Image</button>
		</p>
		  <!-- Image position -->
		<p>
			<label for="">
				<?php echo esc_html__( 'Image Position: '); ?>
			</label>
			<select class="image-position" name="<?php echo esc_attr( $this->get_field_name( 'image_pos' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_pos' ) ); ?>">
				<option value="left" <?php selected( $instance['image_pos'], 'left' ); ?>>
					<?php echo esc_html__( 'Left' ); ?>
				</option>
				<option value="right" <?php selected( $instance['image_pos'], 'right' ); ?>>
					<?php echo esc_html__( 'Right' ); ?>
				</option>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['body_content'] = wp_kses_post( $new_instance['body_content'] );
		$instance['button_href'] = sanitize_text_field( $new_instance['button_href'] );
		$instance['button_name'] = sanitize_text_field( $new_instance['button_name'] );
    $instance['image'] = ! empty( $new_instance['image'] ) ? $new_instance['image'] : '';
		$instance['image_pos'] = sanitize_text_field( $new_instance['image_pos'] );
		return $instance;
	}
}
