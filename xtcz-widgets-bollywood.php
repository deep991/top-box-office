<?php

class Xtcz_Bollywood_Boxoffice_Widgets extends WP_Widget {

	function __construct() {

		$widget_options = array(
			'description' => 'Display Top 5 Bollywood Box office',
			'name' => 'Xtcz Bollywood Box Office'
		);
		parent::__construct( 'Xtcz_Bollywood_Boxoffice_Widgets', '', $widget_options );
	}

	public function form( $instance ) {
		extract( $instance );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input
				class="widefat"
				type="text"
				id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>"
				value="<?php echo (isset( $title ) ? esc_attr( $title ) : '') ?>" />
		</p>		
		<p>
			No, any extra configration required. And it will show 5 Bollywood Box Office Result.
		</p>		
		<?php
	}

	public function widget( $args, $instance ) {

		$savedOptions = get_option( 'xtcz_plugin_options' );
		extract( $args );
		extract( $instance );

		if ( empty( $title ) ) {
			$title = 'Bollywood Box Office';
		}
				
		
		$title = apply_filters( 'widget_title', $title );		

		echo $before_widget;
		echo $before_title . $title . $after_title;
		if ( $bollywoodBoxOfficeList = xtcz_get_bollywood_box_office_list() ) :

			$bollywoodBoxOfficeListArr = json_decode( $bollywoodBoxOfficeList );
			if ( isset( $bollywoodBoxOfficeListArr->error ) ) :
				echo $bollywoodBoxOfficeListArr->error;
			else :
				$this->xtcz_widget_bollywoodview( $bollywoodBoxOfficeListArr );
			endif;
		else:
			noBoxofficeResultFound();
		endif;
		echo $after_widget;
	}

	private function xtcz_widget_bollywoodview( $bollywoodBoxOfficeListArr ) {

		if ( isset( $bollywoodBoxOfficeListArr ) ) {
			$count = 0;
			
			$bollywoodboxOfficeListArr = arrayFromObject($bollywoodBoxOfficeListArr);
			?>
			<table class="xtcz_widget_Bollywood_BoxOffice_list">
			<?php
			foreach ( $bollywoodboxOfficeListArr as $movieInfo ) :
			?>
			<tr id="xtcz-bollywood-<?php echo ++$count; ?>">
				<td>					
					<?php echo $count . ". "; ?>

					<!--Movie Title-->
					<span class='xtcz-widget-bollywood-movie-name'><?php echo $movieInfo[1]; ?></span>
				</td>
				<td>
					<!--Total Movie Earnings-->
					<span class='xtcz-widget-bollywood-movie-earning'><?php echo $movieInfo[3]; ?></span>
				</td>
				
				</tr>
				<?php
			endforeach;
			
			?>
			</table>
			<?php
			echo imp34342dfd();
		}
	}

}

add_action( 'widgets_init', function () {
	register_widget( 'Xtcz_Bollywood_Boxoffice_Widgets' );
} );
?>