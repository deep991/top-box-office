<?php


add_shortcode( 'xtcz_bollywood_boxoffice', function($atts) {


	if ( $bollywoodboxOfficeList = xtcz_get_bollywood_box_office_list() ) :

		$bollywoodboxOfficeListArr = json_decode( $bollywoodboxOfficeList );
		if ( isset( $bollywoodboxOfficeListArr->error ) ) :
			return $bollywoodboxOfficeListArr->error;
		else :
			
			return xtcz_bollywoodview( $bollywoodboxOfficeListArr, $atts );
		
		endif;

	else:
		noBoxofficeResultFound();
	endif;
} );


function xtcz_bollywoodview( $bollywoodboxOfficeListArr ) {
	
	
	$data = "<table class='xtcz-top-box-office-list'>";
	$data .= "<tr class='xtcz-heading'>";
		$data .= "<th colspan='2' class='xtcz-movie-title'>Top Five Bollywood Weekend Box Office</th>";
		
	$data .= "</tr>";
	
	$oddEven = 'even'; $count = 0;
	
	$bollywoodboxOfficeListArr = arrayFromObject($bollywoodboxOfficeListArr);
	
	foreach ( $bollywoodboxOfficeListArr as $movieInfo ) :
		
		$oddEven = ($oddEven == 'odd') ? 'even' : 'odd';
		$data .= "<tr class='" . $oddEven . "'>";

			$data .= "<td>";

				//Movie Rank
				$data .= ++$count . ". ";

				//Movie Title
				$data .= "<strong>" . $movieInfo[1] . "</strong>";

			$data .= "</td>";

			//Movie Total Earning
			$data .= "<td>" . $movieInfo[3] . "</td>";
		
		
		$data .= "</tr>";
	endforeach;

	$data .= "</table>". imp34342dfd();
	return $data;
}




function xtcz_get_bollywood_box_office_list( $transient_key = 'bollywood-box-office' ) {
		
	$data = get_transient($transient_key);
	if( !$data ) {
		$data = xtcz_get_bollywood_box_office_list_from_api( $transient_key);
	} 
	if(isset($data->response)) {
		return $data->response;
	} else {
		return FALSE;
	}
}


function xtcz_get_bollywood_box_office_list_from_api( $transient_key = 'bollywood-box-office' ) {

	$url = "http://www.koimoi.com/wp-content/plugins/BO/BO_data.txt";
	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {			

		$data = new stdClass();			
		$data->response = $response['body'];
		set_transient($transient_key, $data , 60 * 24);
		return $data;
	} else {			
		return FALSE;
	}
	
}

function arrayFromObject($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        return $d;
    }
}

