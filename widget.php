<?php 
/**
 * Adds TrafficMiner widget.
 */
class TrafficMiner_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'trafficminer_widget', // Base ID
			'TrafficMiner Widget', // Name
			array( 'description' => __( 'TrafficMiner Widget', 'text_domain' ), ) // Args
		);
		
		$this->adminOptionsName = 'SharexyTM';
		$tma = get_option( $this->adminOptionsName );
        if ($tma && is_string($tma)) {
            $tma = @unserialize( $tma );
        } elseif (!$tma || !is_array($tma) || empty($tma)) {
            return $tma = array();
        }
		$this->trafficminer_args = $tma;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) 
	{
		extract( $args );
		
		$page_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		if(isset($GLOBALS['post']->ID))
		{
			$post_link = get_permalink($GLOBALS['post']->ID);
		}else{
			$post_link = '';
		}

		$blogurl = get_bloginfo('wpurl'); 
		$width = $instance['width'] ;
		if($width < 200) { $width = 200; }
		$unic_id = md5($blogurl . $page_url . 'sidebar');
		
		$text = "<!--traffic miner widget start--><noindex><div id='shr_widget_tminer_".$unic_id."'><script type='text/javascript'>(function(w) { if (!w.TrafficMiner) { w.TrafficMiner = {};} if (!w.TrafficMiner.Params) { w.TrafficMiner.Params = {}; } w.TrafficMiner.Params['tminer_".$unic_id."'] = {'publisher_key':'". $this->trafficminer_args['user_id'] ."','orientation':'v','background':'". $tm_bg_color ."','width':'". $width ."','label':'". $tm_label ."','ads':'". $showads ."','page_url':'".$page_url."','code_id':'tminer_".$unic_id."'} })(window);</script><script type='text/javascript' src='http://tm.shuttle.sharexy.com/Loader.js'></script></div></noindex><!--traffic miner widget end -->";

		echo $before_widget;
		echo $text;
		echo $after_widget;
	}

	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['width'] = intval(strip_tags( $new_instance['width'] ));
		if($instance['width'] < 200) { $instance['width'] = 200; }
		
		$instance['showads'] =  intval($new_instance['showads']) ;
		
		$instance['tm_label'] = htmlentities(strip_tags($new_instance['tm_label']), ENT_COMPAT, 'UTF-8' );
		
		$instance['tm_bg_color'] = $new_instance['tm_bg_color'];
		if($new_instance['tm_bg_color'] == 'none') { $instance['tm_bg_color'] = 'none'; }
		
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	*/
	public function form( $instance ) {
		
		$width = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : 275;
		$tm_bg_color = isset(  $instance['tm_bg_color'] ) ?  $instance['tm_bg_color'] : '#fff';
		$showads = isset( $instance[ 'showads' ] ) ? $instance[ 'showads' ] : $this->trafficminer_args['showads'];
		$tm_label = isset( $instance[ 'tm_label' ] ) ? $instance[ 'tm_label' ] : $this->trafficminer_args['tm_label'];
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label> 
		<input class="" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /><br />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'tm_bg_color' ); ?>"><?php _e( 'Background color:' ); ?></label><br />
				<input type="radio" name="<?php echo $this->get_field_name( 'tm_bg_color' ); ?>" value="#fff" <?php if($tm_bg_color == '#fff'){ ?> checked="checked" <?php } ?> /> White  
				<input type="radio" name="<?php echo $this->get_field_name( 'tm_bg_color' ); ?>" value="none"  <?php if($tm_bg_color == 'none'){ ?> checked="checked" <?php } ?> /> Transparent
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'showads' ); ?>">
			<input id="<?php echo $this->get_field_id( 'showads' ); ?>" name="<?php echo $this->get_field_name( 'showads' ); ?>" type="checkbox" value="1" <?php if($showads){ ?> checked="checked" <?php } ?>  />
			<?php _e( 'Display ads' ); ?>
		</label> 
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'tm_label' ); ?>"><?php _e( 'Label:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'tm_label' ); ?>" name="<?php echo $this->get_field_name( 'tm_label' ); ?>" type="text" value="<?php echo esc_attr( $tm_label ); ?>"  />
		</p>
		

		<?php 
	}
	
	
} // class TrafficMiner
?>