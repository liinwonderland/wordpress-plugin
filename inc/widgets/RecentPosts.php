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
class RecentPosts extends WP_Widget{

	public $widget_ID;
	public $widget_name;
	public $widget_options = array();
	public $control_options = array();

	function __construct() {
		$this->widget_ID = 'morrigan_recent_posts';
		$this->widget_name = 'Morrigan Recent Posts';
		$this->widget_options = array(
			'classname' => 'morrigan_recent_posts',
			'description' =>__('Recent Posts for Morrigan Theme.'),
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
	}

	public function register(){

		parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );
		add_action( 'widgets_init', array( $this, 'widgetsInit' ) );
	}

	public function widgetsInit(){
		register_widget( $this );
	}

  function widget($args, $instance) {
        $cache = wp_cache_get('widget_recent_posts', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();
        extract($args);

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
			  $btn_text = ( ! empty( $instance['btn_text'] ) ) ? $instance['btn_text'] : 'Read more';
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : true;
				$show_text = isset( $instance['show_text'] ) ? $instance['show_text'] : true;
				$show_category = isset( $instance['show_category'] ) ? $instance['show_category'] : true;
				$differentFirstPost = isset( $instance['differentFirstPost'] ) ? $instance['differentFirstPost'] : true;

				$nothumbnail = plugins_url("morrigan-plugin/assets/placeholder-image.jpg" );

			// array to call recent posts
				if( ! $cats = $instance["cats"] )  $cats='';
				$rp_args=array(
					'showposts' => $number,
					'category__in'=> $cats,
				);
				$r = null;
				$r = new WP_Query($rp_args);

        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>

				<?php if ( $title) :?>
					<h1 tabindex="0">  <?php echo  $title ; ?></h1>
				<?php endif; ?>
			<div class="container-fluid">
				<ul class="recent-posts-ul row">
        <?php while ( $r->have_posts() ) : $r->the_post();
					if($differentFirstPost){
						$c++;
						$class = ($c == 1) ? 'col-sm-12' : 'col-md-4';
						$class1 = ($c == 1) ? 'text-middle card-img-overlay text-white' : 'card-body';
						$class2 = ($c == 1) ? 'recentpost-bg' : '';
					}else{
						$class = 'col-md-4';
						$class1 =  'card-body';
						$class2 = '';
					}
				?>
					<?php if( 'post_style' == $instance['display'] ){ ?>
	            <li data-aos="zoom-in" data-aos-duration="900" class="recentposts recentpost-post <?php echo $class; ?>" >
								<div class="card">
									<div class="image-outer-div">
										<div class="card-img-top background-image" style="background-image:url(
												<?php  if(has_post_thumbnail()){ echo esc_attr(the_post_thumbnail_url());}else{echo esc_attr($nothumbnail);}?>">
	                  </div>
										<div class="<?php echo $class2; ?>">

										</div>
									</div>

										<?php if ( $show_category) : ?>
											<h5 class="recentpost-badge pos-right position-absolute">
												<?php
												$categories = wp_get_post_terms( get_the_id(), 'category' );

												if ( $categories ):
												    foreach ( $categories as $category ): ?>
												        <a href="<?php echo get_term_link( $category->term_id, 'category' ); ?>" class="badge badge-pill badge-primary d-sm-none d-md-block"><?php echo $category->name; ?></a>
												    <?php endforeach;
												endif;
												 ?>
											</h5>
										<?php endif; ?>
	                  <div class="<?php echo $class1; ?>">
											<div class="col-md-12">


												<?php if($c == 1){ ?>
													<h3 tabindex="0"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></h3>
													<?php if ( $show_date ) : ?>
															<p tabindex="0" class="card-text font-italic"><i class="far fa-clock"></i> <?php echo get_the_date(); ?></p>
													<?php endif; ?>
													<?php if ( $show_text ) : ?>
														<p tabindex="0" class="card-text"><?php echo excerpt(60); ?></p>
													<?php endif; ?>
												<?php }else {?>

													<h5 tabindex="0" class="card-title"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></h5>
													<?php if ( $show_date ) : ?>
															<p tabindex="0" class="card-text font-italic"><i class="far fa-clock"></i> <?php echo get_the_date(); ?></p>
													<?php endif; ?>
													<?php if ( $show_text ) : ?>
														<p tabindex="0" class="card-text"><?php echo excerpt(10); ?></p>
													<?php endif; ?>
												<?php } ?>
												  <a role="button" class="btn btn-outline-primary category-btn" href="<?php the_permalink(); ?>"><span><?php if ( $btn_text ) echo  $btn_text ; ?></span><i class="fas fa-angle-double-right"></i></a>

											</div>
	                  </div>
								</div>
	            </li>
					<?php }else{ ?>
						<li class="col-12 recentposts recentpost-list">
							<a href="<?php the_permalink(); ?>" class="row">
								<div class="image-outer-div">
									<div class="background-image" style="background-image:url(
											<?php  if(has_post_thumbnail()){ echo esc_attr(the_post_thumbnail_url());}else{echo esc_attr($nothumbnail);}?>">
									</div>
								</div>
								<div class="col-9 row">
									<div class="mor-rp-text">
										<h5 class="card-title" tabindex="0"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></h5>
										<?php if ( $show_date ) : ?>
												<p  tabindex="0" class="card-text font-italic"><i class="far fa-clock"></i> <?php echo get_the_date(); ?></p>
										<?php endif; ?>
										<?php if ( $show_text ) : ?>
											<p  tabindex="0" class="card-text d-none d-sm-block d-md-none d-lg-block"><?php echo excerpt(10); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</a>
						</li>
					<?php } ?>
        <?php endwhile; ?>
        </ul>
			</div>

        <?php echo $after_widget; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_recent_posts', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = (bool) $new_instance['show_date'];
				$instance['show_text'] = (bool) $new_instance['show_text'];
				$instance['show_category'] = (bool) $new_instance['show_category'];
        $this->flush_widget_cache();
				$instance['differentFirstPost'] = (bool) $new_instance['differentFirstPost'];
        $this->flush_widget_cache();
        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

				$instance['display'] = ( !empty( $new_instance['display'] ) ) ? sanitize_text_field( $new_instance['display'] ) : '';
				$instance['textcolor'] = strip_tags( $new_instance['textcolor']);
				$instance['cats'] = $new_instance['cats'];
				$instance['btn_text'] = strip_tags($new_instance['btn_text']);
        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_recent_posts', 'widget');
    }

    function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
				$show_text = isset( $instance['show_text'] ) ? (bool) $instance['show_text'] : true;
				$show_category = isset( $instance['show_category'] ) ? (bool) $instance['show_category'] : true;
				$differentFirstPost = isset( $instance['differentFirstPost'] ) ? (bool) $instance['differentFirstPost'] : true;
				$display = isset($instance['display']) ? sanitize_text_field( $instance['display'] ) : null;
?>
				<!-- Title  -->
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

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
				<!-- Number of posts to show  -->
        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

				<!-- How to display  -->
				<p><label for="<?php echo $this->get_field_id( 'list_style' ); ?>"><?php _e( 'How to display:' ); ?></label></p>
				<!-- Display as LIST -->
				<p>
					<label>
							 <input type="radio" value="list_style" name="<?php echo $this->get_field_name( 'display' ); ?>" <?php checked( $display, 'list_style' ); ?> id="<?php echo $this->get_field_id( 'display' ); ?>" />
							 <?php esc_attr_e( 'As list', 'text_domain' ); ?>
					 </label>
				</p>
				<!-- Display as category  -->
				<p>
					<label>
							 <input type="radio" value="post_style" name="<?php echo $this->get_field_name( 'display' ); ?>" <?php checked( $display, 'post_style' ); ?> id="<?php echo $this->get_field_id( 'display' ); ?>" />
							 <?php esc_attr_e( 'As category', 'text_domain' ); ?>
					 </label>
				</p>
				<!-- Show date -->
        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date or not' ); ?></label></p>

				<!-- Show posts  text  -->
				<p><input class="checkbox" type="checkbox" <?php checked( $show_text ); ?> id="<?php echo $this->get_field_id( 'show_text' ); ?>" name="<?php echo $this->get_field_name( 'show_text' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_text' ); ?>"><?php _e( 'Display posts text or not' ); ?></label></p>

				<!-- Show posts category  -->
				<p><input class="checkbox" type="checkbox" <?php checked( $show_category ); ?> id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display post category or not (only in Category View)' ); ?></label></p>

				<!-- Different first post view -->
				<p><input class="checkbox" type="checkbox" <?php checked( $differentFirstPost ); ?> id="<?php echo $this->get_field_id( 'differentFirstPost' ); ?>" name="<?php echo $this->get_field_name( 'differentFirstPost' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'differentFirstPost' ); ?>"><?php _e( 'Different first post style (only in Category View)' ); ?></label></p>
				<!-- Button text -->
				<p><label for="<?php echo $this->get_field_id( 'btn_text' ); ?>"><?php _e( 'Button text (Read more):' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'btn_text' ); ?>" name="<?php echo $this->get_field_name( 'btn_text' ); ?>" type="text" value="<?php echo $btn_text; ?>" /></p>

<?php
    }
}
