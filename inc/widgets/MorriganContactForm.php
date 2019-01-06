<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Widgets;
use WP_Widget;
/**
*
*/
class MorriganContactForm extends WP_Widget{

  	public $widget_ID;
  	public $widget_name;
  	public $widget_options = array();
  	public $control_options = array();

  	function __construct() {
  		$this->widget_ID = 'morrigan_contact_from';
  		$this->widget_name = 'Contact Form';
  		$this->widget_options = array(
  			'classname' => 'contact-form-widget',
  			'description' =>__('A customizable contact form. Can choose to add infomation on the left side of Contact Form.'),
  			'customize_selective_refresh' => true,
  		);
  		$this->control_options = array(
  			'width' => '100%',
  			'height' => 350,
  		);
  		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  	}

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
    // Front view style
    public function widget( $args, $instance ) {
      echo $args['before_widget'];
    	$class1 = (( 'on' == $instance['text_display'] ) ? 'col-md-6 the-contact-form' : 'col-md-12');
      $class2 = (( 'on' == $instance['text_display'] ) ? 'contact-form' : 'no-text');
      ?>

      <div class="<?php echo esc_attr( $class2 ); ?>">

        <?php if('on' == $instance['text_display']){ ?>
          <div class="col-md-6 contact-information">
            <!-- Contact Form title-->
            <?php if ( ! empty( $instance['title'] ) ) { ?>
              <h1 tabindex="0" data-aos="zoom-in" data-aos-duration="1000"><?php echo esc_attr( $instance['title'] ); ?></h1>
            <?php } ?>
            <!-- Contact Form title 2-->
            <?php if ( ! empty( $instance['title2'] ) ) { ?>
              <h3 tabindex="0" data-aos="zoom-in" data-aos-duration="1000" class="text-uppercase"><?php echo esc_attr( $instance['title2'] ); ?></h3>
            <?php } ?>
              <!-- Contact Form description -->
            <?php if ( ! empty( $instance['description'] ) ) { ?>
              <p tabindex="0" data-aos="zoom-in" data-aos-duration="1000"><?php echo esc_attr( $instance['description'] ); ?></p>
            <?php } ?>
              <!-- Contact Form Phone-->
            <?php if ( ! empty( $instance['phone'] ) ) { ?>
              <h5 data-aos="zoom-in-right" data-aos-duration="500" > <i class="fas fa-phone"></i> Phone </h5>
              <p tabindex="0" data-aos="zoom-in" data-aos-duration="1000" ><?php echo esc_attr( $instance['phone'] ); ?></p>
            <?php } ?>
            <!-- Contact Form Phone-->
            <?php if ( ! empty( $instance['email'] ) ) { ?>
              <h5 data-aos="zoom-in-right" data-aos-duration="500" ><i class="far fa-envelope"></i> Email </h5>
              <p tabindex="0" data-aos="zoom-in" data-aos-duration="1000" ><?php echo esc_attr( $instance['email'] ); ?></p>
            <?php } ?>
            <!-- Contact Form Phone-->
            <?php if ( ! empty( $instance['hours'] ) ) { ?>
                <h5 data-aos="zoom-in-right" data-aos-duration="500" ><i class="fas fa-home"></i> Working Hours</h5>
              <p tabindex="0" data-aos="zoom-in" data-aos-duration="1000" ><?php echo esc_attr( $instance['hours'] ); ?></p>
            <?php } ?>
          </div>
        <?php } ?>
          <div class="<?php echo esc_attr( $class1); ?>">
            <?php if(!'on' == $instance['text_display']){ ?>
              <?php if ( ! empty( $instance['title'] ) ) { ?>
                <h1 tabindex="0"><?php echo esc_attr( $instance['title'] ); ?></h1>
              <?php } ?>
              <!-- Contact Form title 2-->
              <?php if ( ! empty( $instance['title2'] ) ) { ?>
                <h3 tabindex="0" class="text-uppercase"><?php echo esc_attr( $instance['title2'] ); ?></h3>
              <?php } ?>
            <?php } ?>
            <div class="contact-form-trs">

            </div>
          <?php  include('wp-content/themes/morrigan/template-parts/contact-form.php'); ?>
          </div>
      </div>
    <?php
    echo $args['after_widget'];
    }

    // For back-end view (widget form creation)
    public function form( $instance ) {
      // Check values
      if( $instance) {
        $title = esc_attr($instance['title']);
        $title2 = esc_attr($instance['title2']);
        $description = $instance['description'];
        $phone = esc_attr($instance['phone']);
        $email = esc_attr($instance['email']);
        $hours = esc_attr($instance['hours']);
      } else {
        $title = '';
        $title2 = '';
        $description = '';
        $phone = '';
        $email = '';
        $hours = '';
      };
    ?>
    <!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Contact Form Title' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title2' ) ); ?>"><?php esc_attr_e( 'Contact Form Title 2' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title2' ) ); ?>" type="text" value="<?php echo esc_attr( $title2 ); ?>">
    </p>
    <!-- Text display show / hide -->
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'text_display' ) ); ?>">
        <?php echo esc_html__( 'Display text: '); ?>
      </label>
        <input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'text_display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_display' ) ); ?>" class="" value="on"
          <?php checked( $instance['text_display'], 'on' ); ?>>
        <label class="onoffswitch-label" for="<?php echo esc_attr( $this->get_field_name( 'text_display' ) ); ?>"></label>
    <p>
        <!-- Contact Form Description-->
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
      Contact Form Description: </label>
      <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="7" cols="20" ><?php echo $description; ?></textarea>
		</p>
    <!-- Phone -->
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_attr_e( 'Phone' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
    </p>
    <!-- Email -->
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
    </p>
    <!-- Working Hours -->
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>"><?php esc_attr_e( 'Working hours' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>" type="text" value="<?php echo esc_attr( $hours ); ?>">
    </p>
    <?php
    }


	public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    $instance['title2'] = sanitize_text_field( $new_instance['title2'] );
    $instance['description'] = sanitize_text_field( $new_instance['description'] );
    $instance['phone'] = sanitize_text_field( $new_instance['phone'] );
    $instance['email'] = sanitize_text_field( $new_instance['email'] );
    $instance['hours'] = sanitize_text_field( $new_instance['hours'] );
  	$instance['text_display'] = ( ! empty( $new_instance['text_display'] ) ) ? esc_html( $new_instance['text_display'] ) : '';

    return $instance;
  }
}
