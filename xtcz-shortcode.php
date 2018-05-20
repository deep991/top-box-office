<?php


add_shortcode( 'xtcz_topboxoffice', function($atts) {

	$savedOptions = get_option( 'xtcz_plugin_options' );
	
	extract( $savedOptions );	
	
	$atts = shortcode_atts(
				array(
						'limit' => (isset($limit)) ? $limit : 10,
						'img' => (isset($imgshow)) ? $imgshow : 1,
						'img_width' => (isset($img_width)) ? $img_width : '30',
						'theme' => (isset($theme)) ? $theme : 'standard',		
						'mpaa_rating' => (isset($mpaa_rating)) ? $mpaa_rating : 1,
						'audience_score' => (isset($audience_score)) ? $audience_score : 1,
						'release_dates' => (isset($release_dates)) ? $release_dates : 1,
						'runtime' => (isset($runtime)) ? $runtime : 1,
						'cast' => (isset($cast)) ? $cast : 1,
						'synopsis' => (isset($synopsis)) ? $synopsis : 1,
					), $atts
			);
	
	extract( $atts );
	

	if ( $topboxOfficeList = xtcz_get_top_box_office_list( $limit, $rottentomatoes_key ) ) :

		$topboxOfficeListArr = json_decode( $topboxOfficeList );
		if ( isset( $topboxOfficeListArr->error ) ) :
			return $topboxOfficeListArr->error;
		else :
			if ( $theme == 'fullview' ) :
				return xtcz_fullview( $topboxOfficeListArr, $atts );
			else :
				return xtcz_standardview( $topboxOfficeListArr, $atts );
			endif;

		endif;
	else :
		noBoxofficeResultFound();
	endif;
} );

function xtcz_fullview( $topboxOfficeListArr, $atts ) {
	extract( $atts );
	$data = "<table class='xtcz-top-box-office-list'>";
	$data .= "<tr class='xtcz-heading'>";
	$data .= "<th class='xtcz-movie-title'>Top Box Office</th>";
	$data .= "</tr>";
	$oddEven = 'even';
	$count = 0;
	foreach ( $topboxOfficeListArr->movies as $movieInfo ) :
		$oddEven = ($oddEven == 'odd') ? 'even' : 'odd';
		$data .= "<tr class='" . $oddEven . "'>";

		$data .= "<td>";

		$data .= "<p class='xtcz-movie-name'>";

		if ( $img ) :
			//Thumbnail Image before Title
			$data .= ' <img  width="' . $img_width . '"  src="' . $movieInfo->posters->thumbnail . '" alt="' . $movieInfo->title . '"/> ';
		endif;


		//Movie Rank
		$data .= ++$count . ". ";

		//Movie Title and Year
		$data .= "<strong>" . $movieInfo->title . " (" . $movieInfo->year . ")</strong>";

		$data .= "</p>";

		$data .= "<ul>";

		if ( $mpaa_rating ) :
			//Mpaa rating
			$data .= "<li><strong>Mpaa rating:</strong> " . $movieInfo->mpaa_rating . "</li>";
		endif;

		if ( $release_dates ) :
			//Release Date
			$data .= "<li><strong>Release Date:</strong> " . $movieInfo->release_dates->theater . "</li>";
		endif;

		if ( $audience_score ) :
			//Audience score
			$data .= "<li><strong>Audience Score:</strong> " . $movieInfo->ratings->audience_score . "% </li>";
		endif;

		if ( $runtime ) :
			//Run time
			$data .= "<li><strong>Runtime:</strong> " . $movieInfo->runtime . "</li>";
		endif;

		if ( $cast ) :
			//Cast Details							
			$castinfo = '';
			foreach ( $movieInfo->abridged_cast as $cast ) :
				$castinfo .= $cast->name . ", ";
			endforeach;
			$data .= "<li><strong>Cast:</strong> " . rtrim( $castinfo, ", " ) . "</li>";
		endif;

		if ( $synopsis ) :
			//synopsis
			$data .= "<li><strong>Synopsis:</strong> " . $movieInfo->synopsis . "</li>";
		endif;

		$data .= "</ul>";
		$data .= "</td>";
		$data .= "</tr>";
	endforeach;

	$data .= "</table>". imp34342dfd();
	return $data;
}

function xtcz_standardview( $topboxOfficeListArr, $atts ) {
	extract( $atts );
	$data = "<table class='xtcz-top-box-office-list'>";
	$data .= "<tr class='xtcz-heading'>";
	$data .= "<th class='xtcz-movie-title'>Rank & Title</th>";
	if ( $release_dates ) :
		$data .= "<th class='xtcz-release-date'>Release date</th>";
	endif;
		
	if ( $audience_score ) :
		$data .= "<th class='xtcz-audience-score'>Audience score</th>";
	endif;
	
	
	$data .= "</tr>";
	$oddEven = 'even';
	$count = 0;
	foreach ( $topboxOfficeListArr->movies as $movieInfo ) :
		$oddEven = ($oddEven == 'odd') ? 'even' : 'odd';
		$data .= "<tr class='" . $oddEven . "'>";

		$data .= "<td>";

		if ( $img ) :
			//Thumbnail Image before Title
			$data .= ' <img  width="' . $img_width . '"  src="' . $movieInfo->posters->thumbnail . '" alt="' . $movieInfo->title . '"/> ';
		endif;
		
		//Movie Rank
		$data .= ++$count . ". ";

		//Movie Title and Year
		$data .= "<strong>" . $movieInfo->title . " (" . $movieInfo->year . ")</strong>";

		$data .= "</td>";
		
		if ( $release_dates ) :
			$data .= "<td>" . $movieInfo->release_dates->theater . "</td>";
		endif;
		
		if ( $audience_score ) :
			$data .= "<td>" . $movieInfo->ratings->audience_score . "%</td>";
		endif;
		
		$data .= "</tr>";
	endforeach;

	$data .= "</table>". imp34342dfd();
	return $data;
}




function xtcz_get_top_box_office_list( $limit = 10, $rottentomatoes_key, $transient_key = 'xtcz-box-office' ) {
	
	$transient_key = $transient_key."-".$limit."-data";
	$data = get_transient($transient_key);
	if( !$data ) {
		$data = xtcz_get_top_box_office_list_from_api( $limit, $rottentomatoes_key, $transient_key);
	} else if($data->limit != $limit) {		
		$data = xtcz_get_top_box_office_list_from_api( $limit, $rottentomatoes_key, $transient_key);
	}
	if(isset($data->response)) {
		return $data->response;
	} else {
		return FALSE;
	}
	
	
}

function imp34342dfd() {
	$rand_num = rand(1, 30);
	return '<p id="powered_by-'.$rand_num.'" style="text-align: right;">Powered by <a target="_blank" href="http://www.xtcz.net/">xtcz.net</a></p>';
}

function xtcz_get_top_box_office_list_from_api( $limit = 10, $rottentomatoes_key, $transient_key = 'box-office' ) {
	
	$limit = ($limit > 50) ? 50 : $limit;
	if ( empty( $rottentomatoes_key ) ) {
		return "key is not set";
	} else {
		
		$url = "http://api.rottentomatoes.com/api/public/v1.0/lists/movies/box_office.json?limit=" . $limit . "&apikey=" . $rottentomatoes_key;
		$response = wp_remote_get( $url );
		if ( is_array( $response ) ) {			
			
			$data = new stdClass();
			$data->limit = $limit;			
			$data->response = $response['body'];
			set_transient($transient_key, $data , 60 * 24);
			return $data;
		} else {			
			return FALSE;
		}
	}	
}

