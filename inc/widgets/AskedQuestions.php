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
class AskedQuestions extends WP_Widget{

	public $widget_ID;
	public $widget_name;
	public $widget_options = array();
	public $control_options = array();

	function __construct() {
		$this->widget_ID = 'morrigan_question';
		$this->widget_name = 'Frequently asked question';
		$this->widget_options = array(
			'classname' => 'morrigan_question col-md-12',
			'description' => __('Displays your frequently asked questions. Outputs the question, title and answer per listing.', 'morrigan'),
			'customize_selective_refresh' => true,
		);
		$this->control_options = array(
			'width' => '100%',
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
		?>
		<div class="row justify-content-center" data-aos="fade-down" data-aos-duration="1000">
			<div class="col-md-8">
				<?php if ( ! empty( $instance['title'] ) ) { ?>

	        <h2><?php echo esc_attr( $instance['title'] ); ?></h2>
					<?php if ( ! empty( $instance['content'] ) ) { ?>

						<p><?php echo esc_attr( $instance['content'] ); ?></p>

					<?php } ?>
			</div>

				<?php

				$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post_type' => array('questions')));

        if ($r->have_posts()) :?>
	        <?php echo $before_widget;?>

	        <?php if ( $title ) echo $before_title . $title . $after_title; ?>

	        <div class="col-md-8 accordion" id="questions">
		        <?php  while ($r->have_posts()) : $r->the_post();
							$c++;
							$class1 = ($c == 1) ? 'show' : '';
						?>
							<div class="card">
						    <div class="card-header shadow-sm" id="headingOne">
						      <h5 class="mb-0">

						        <button class="btn btn-link btn-fullwidth" type="button" data-toggle="collapse" data-target="#question<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="question<?php echo get_the_ID(); ?>">
											<p class="btn-faq"><i class="far fa-question-circle"></i> <?php if ( get_the_title() ) the_title(); else the_ID(); ?><span class="faq-caret-down">	<i class="fas fa-caret-down"></i></span></p>



						        </button>

						      </h5>
						    </div>
								<div id="question<?php echo get_the_ID(); ?>" class="collapse <?php echo $class1; ?>" aria-labelledby="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" data-parent="#accordionExample">
						      <div class="card-body col-md-10">
						     		<?php echo excerpt(100); ?>
						      </div>
						    </div>
						 </div>
	        <?php endwhile; endif; ?>
				</div>
      <?php } ?>
			<?php if ( ! empty( $instance['btnText'] ) && ! empty($instance['button_url']) ) { ?>
				<div class="col-md-8 text-right">
					<a role="button" id="<?php  echo esc_attr( $this->get_field_id( 'button_url' ) );?>" href="<?php echo esc_attr( $instance['button_url'] ); ?>" class="btn btn-str faq-button" name="button"><?php echo esc_attr( $instance['btnText'] ); ?></a>
				</div>
			</div>
			<?php } ?>

		<?php
		echo $args['after_widget'];
	}
 	// For back-end view
	public function form( $instance ) {
		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$content = $instance['content'];
			$btnText = $instance['btnText'];
			$button_url = esc_attr($instance['button_url']);
		} else {
			$title = '';
			$content = '';
			$btnText = '';
			$button_url = '';
		};
		?>
				<!-- Title -->
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

				<!-- Descrition -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_attr_e( 'Description:' ); ?> </label>
				<textarea class="widefat" id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" rows="7" cols="20" ><?php echo $content; ?></textarea>
			</p>
			<!-- Button text -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btnText' ) ); ?>"><?php esc_attr_e( 'Button text' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btnText' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btnText' ) ); ?>" type="text" value="<?php echo esc_attr( $btnText ); ?>">
					<!-- Button url -->
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>">
					<?php echo esc_html__( 'Button URL: ' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['button_url'] ); ?>">
			</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['content'] = wp_kses_post( $new_instance['content'] );
		$instance['btnText'] = sanitize_text_field( $new_instance['btnText'] );
		$instance['button_url'] = sanitize_text_field( $new_instance['button_url'] );
		return $instance;
	}
}
