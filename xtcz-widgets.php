<?php

class Xtcz_Boxoffice_Widgets extends WP_Widget {

	function __construct() {

		$widget_options = array(
			'description' => 'Display Top Box office movies list',
			'name' => 'Xtcz Top Box Office'
		);
		parent::__construct( 'Xtcz_Boxoffice_Widgets', '', $widget_options );
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
			<label for="<?php echo $this->get_field_id( 'movie_limit' ); ?>">Number of Movies to Show:</label>
			<input				
				type="number"
				style="width: 45px"
				id="<?php echo $this->get_field_id( 'movie_limit' ); ?>"
				name="<?php echo $this->get_field_name( 'movie_limit' ); ?>"
				value="<?php echo (isset( $movie_limit ) ? esc_attr( $movie_limit ) : '5') ?>" />
		</p>
		<p>
			<input
				class="widefat"
				type="checkbox"
				id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"
				name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>"
				<?php echo (isset( $show_thumbnail ) ? 'checked' : '') ?>
				value="1" />
			<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>">Show Thumbnail</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>">Thumbnail Width:</label>
			<input
				class="widefat"
				type="text"
				id="<?php echo $this->get_field_id( 'thumb_width' ); ?>"
				name="<?php echo $this->get_field_name( 'thumb_width' ); ?>"
				value="<?php echo (isset( $thumb_width ) ? esc_attr( $thumb_width ) : '') ?>" />
		</p>
		<?php
	}

	public function widget( $args, $instance ) {

		$savedOptions = get_option( 'xtcz_plugin_options' );
		extract( $args );
		extract( $instance );

		if ( empty( $title ) ) {
			$title = 'Top Box Office';
		}
		
		if ( empty( $movie_limit ) ) {
			$movie_limit = 5;
		}
		
		if ( empty( $show_thumbnail ) ) {
			$show_thumbnail = FALSE;
		}
		
		if ( empty( $thumb_width ) ) {
			$thumb_width = FALSE;
		}
		
		$title = apply_filters( 'widget_title', $title );
		$movie_limit = apply_filters( 'widget_movie_limit', $movie_limit );
		$show_thumbnail = apply_filters( 'widget_show_thumbnail', $show_thumbnail );
		$thumb_width = apply_filters( 'widget_thumb_width', $thumb_width );

		

		echo $before_widget;
		echo $before_title . $title . $after_title;
		if ( $topboxOfficeList = xtcz_get_top_box_office_list( $movie_limit, $savedOptions['rottentomatoes_key'] ) ) :

			$topboxOfficeListArr = json_decode( $topboxOfficeList );
			if ( isset( $topboxOfficeListArr->error ) ) :
				echo $topboxOfficeListArr->error;
			else :
				$this->xtcz_widget_fullview( $topboxOfficeListArr, $show_thumbnail, $thumb_width );
			endif;
		else :
			noBoxofficeResultFound();
		endif;
		echo $after_widget;
	}

	private function xtcz_widget_fullview( $topboxOfficeListArr, $show_thumbnail = FALSE, $img_width = '30' ) {

		if ( isset( $topboxOfficeListArr ) ) {
			$count = 0;
			?>
		<ul class="xtcz_widget_Top_BoxOffice_list">
			<?php
			foreach ( $topboxOfficeListArr->movies as $movieInfo ) :
			echo "<li id='xtcz-movie-".++$count."'>";
				if ( $show_thumbnail ) :
					//Thumbnail Image before Title
					echo ' <img  width="' . $img_width . '"  src="' . $movieInfo->posters->thumbnail . '" alt="' . $movieInfo->title . '"/> ';
				endif;

				//Movie Rank
				echo $count . ". ";

				//Movie Title and Year
				echo "<span class='xtcz-widget-movie-name'>" . $movieInfo->title . " (" . $movieInfo->year . ")</span>";
				
				if ( $show_thumbnail ) :
					echo '<div style="clear:both"></div>';
				endif;
				
				echo "</li>";				
			endforeach;
			
			?>
			</ul>
			<?php
			echo imp34342dfd();
		}
	}

}

add_action( 'widgets_init', function () {
	register_widget( 'Xtcz_Boxoffice_Widgets' );
} );
?>