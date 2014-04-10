<?php
/*
 * WIDGET :: Suburb Profile
 */

class EPL_Widget_Suburb_Profile extends WP_Widget {

	function __construct() {
		parent::WP_Widget( false, $name = __('EPL - Suburb Profile', 'epl') );
	}

	function widget($args, $instance) {	
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']);
		$p_number	= $instance['p_number'];
		$p_skip		= $instance['p_skip'];
		$location	= $instance['location'];
		$display	= $instance['display'];
		$image		= $instance['image'];
		$d_align	= $instance['d_align'];
		$sort		= $instance['sort'];
		$random		= $instance['random'];
		if ( $p_number == '' ) {
			$p_number = 1;
		}
		if ( $random == 'on' ) {
			$random = 'rand';
		}
		
		echo $before_widget;
		
		$au_id = get_the_author_meta( 'ID' );
		$query = new WP_Query( array (
			'post_type' => 'epl_suburb',
			'author' => $au_id,
			'showposts' => $p_number,
			'order' => $sort,
			'orderby' => $random
		) );
			
		if( $query->have_posts() ) : 
			if ( $title )
				echo $before_title . $title . $after_title;

			while($query->have_posts()) : $query->the_post();
				epl_suburb_card( $image);
				wp_reset_query();
			endwhile;
		endif;
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['display'] = strip_tags($new_instance['display']);
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['d_align'] = strip_tags($new_instance['d_align']);
		$instance['p_number'] = strip_tags($new_instance['p_number']);
		$instance['p_skip'] = strip_tags($new_instance['p_skip']);
		$instance['location'] = strip_tags($new_instance['location']);
		$instance['sort'] = strip_tags($new_instance['sort']);
		$instance['random'] = strip_tags($new_instance['random']);
		return $instance;
	}

	function form($instance) {
		$title 		= esc_attr($instance['title']);
		$display	= esc_attr($instance['display']);
		$image		= esc_attr($instance['image']);
		$d_align	= esc_attr($instance['d_align']);
		$p_number	= esc_attr($instance['p_number']);
		$p_skip		= esc_attr($instance['p_skip']);
		$location	= esc_attr($instance['location']);
		$sort		= esc_attr($instance['sort']);
		$random		= esc_attr($instance['random']); ?>
	
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Size'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>">
				<?php
					$sizes = get_thumbnail_sizes();
					foreach ($sizes as $k=>$v) {
						$v = implode(" x ", $v);
						echo '<option class="widefat" value="' . $k . '" id="' . $k . '"', $instance['image'] == $k ? ' selected="selected"' : '', '>', $k . ' (' . $v . ' )', '</option>';
					}
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('d_align'); ?>"><?php _e('Image Alignment'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('d_align'); ?>" name="<?php echo $this->get_field_name('d_align'); ?>">
				<?php
					$options = array('none', 'alignleft', 'alignright', 'aligncenter');
					foreach ($options as $option) {
						echo '<option value="' . $option . '" id="' . $option . '"', $instance['d_align'] == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('p_number'); ?>"><?php _e('Number of Suburb Profiles'); ?></label>
			<select id="<?php echo $this->get_field_id('p_number'); ?>" name="<?php echo $this->get_field_name('p_number'); ?>">
				<?php
					for ($i=1;$i<=20;$i++) {
						echo '<option value="'.$i.'"'; if ($i==$instance['p_number']) echo ' selected="selected"'; echo '>'.$i.'</option>';
					}
				?>
			</select>
		</p>
		
		<p>
			<select id="<?php echo $this->get_field_id('p_skip'); ?>" name="<?php echo $this->get_field_name('p_skip'); ?>">
				<?php
					for ($i=0;$i<=20;$i++) {
						echo '<option value="'.$i.'"'; 	if ($i==$instance['p_skip']) echo ' selected="selected"'; echo '>'.$i.'</option>';
					}
				?>
			</select>
			<label for="<?php echo $this->get_field_id('p_skip'); ?>"><?php _e('Number of Suburb Profiles to Skip'); ?></label>
		</p>
		
		<p>
			<select class="widefat" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
				<?php
					$options = array('DESC', 'ASC');
					foreach ($options as $option) {
						echo '<option value="' . $option . '" id="' . $option . '"', $instance['sort'] == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
				?>
			</select>
			<label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort Order'); ?></label>
		</p>
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('display'); ?>" name="<?php echo $this->get_field_name('display'); ?>" <?php if ($instance['display']) echo 'checked="checked"' ?> />
		<label for="<?php echo $this->get_field_id('display'); ?>"><?php _e('Display Featured Image'); ?></label>
		</p>
		
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" <?php if ($instance['location']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('location'); ?>"><?php _e('Display Suburb Name'); ?></label>
		</p>
		
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('random'); ?>" name="<?php echo $this->get_field_name('random'); ?>" <?php if ($instance['random']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('random'); ?>"><?php _e('Random'); ?></label>
		</p>
		<?php 
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("EPL_Widget_Suburb_Profile");') );
