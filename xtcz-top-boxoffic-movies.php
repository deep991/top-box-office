<?php
/*
 * Plugin Name: Top Box Office
 * Description:  Top Box office provides real time ranking of the box office and always keep users upto date with latest box office results. The data updates automatically every week, no need to do any extra work.
 * Version: 1.0
 * License:  GNU General Public License 3.0 or newer (GPL) http://www.gnu.org/licenses/gpl.html
 * Author: Deepak Kumar
 */

class Xtcz_TopBoxOffice_Options
{

	public $options;
	public function __construct()
	{

		$this->options = get_option('xtcz_plugin_options');
		$this->xtcz_register_settings_and_fields();
	}

	static public function xtcz_add_menu_admin()
	{
		add_options_page('Settings: Xtcz Top Box Office', 'XTCZ Box Office', 'administrator', __FILE__, array('Xtcz_TopBoxOffice_Options', 'xtcz_display_menu_options_admin'));
	}

	static public function xtcz_display_menu_options_admin()
	{
		$optionsValues = get_option('xtcz_plugin_options');

		?>
		<div class="wrap">
			<div class="setting-box" style="float: left; max-width: 700px;">
				<h2>XTCZ Top Box Office Settings</h2>
				<form method="post" action="options.php">
					<?php 
				settings_fields('xtcz_plugin_options'); //option_group
				do_settings_sections(__FILE__);
				?>
					<p class="submit">
						<input type="submit" name="submit" class="button-primary" value="Save Changes" />
					</p>
				</form>
				<h3>ShortCode</h3>
				<?php
			if (isset($optionsValues['rottentomatoes_key']) && !empty($optionsValues['rottentomatoes_key'])) :
			?>							
							Use this <code>[xtcz_topboxoffice]</code> shortcode to display top box office movies list. 
							<br />
							Use this <code>&lt;?php echo do_shortcode('[xtcz_topboxoffice]'); ?&gt;</code>  php code to display top box office movie list into wordpress theme file.

							<h4>Optional parameters</h4>
							You can also use optional parameters to override saved setting options.
							<br />
							For example:
							<br />
							<code>[xtcz_topboxoffice theme="standard" img_width="25"]</code>
							<h4>List of optional parameters</h4>
							<ul>
								<li><code>theme</code>: 'fullview' or 'standard' .</li>
								<li><code>limit</code>: Numeric value to show number of movies in the top box office list.</li>
								<li><code>img</code>: Boolean value ( 1 or 0 ) to show/hide thumbnail image.</li>														
								<li><code>img_width</code>: Numeric value ( 1 or 0 ) to set width of thumbnail image.</li>					
								<li><code>mpaa_rating</code>: Boolean value ( 1 or 0 ) to show/hide movie's Mpaa rating.</li>							
								<li><code>audience_score</code>: Boolean value ( 1 or 0 ) to show/hide movie's Audience Score.</li>							
								<li><code>release_dates</code>: Boolean value ( 1 or 0 ) to show/hide movie's Release dates.</li>							
								<li><code>runtime</code>: Boolean value ( 1 or 0 ) to show/hide movie's Runtime.</li>							
								<li><code>cast</code>: Boolean value ( 1 or 0 ) to show/hide movie's Cast.</li>							
								<li><code>synopsis</code>: Boolean value ( 1 or 0 ) to show/hide movie's synopsis.</li>							
							</ul>
						<?php 
					else :
						echo "<p class='notice'><strong>Rottentomatoes Key</strong> is required to view working shortcode.</p>";
					endif;
					?>
					<h3>Bollywood Box ShortCode</h3>
					<p>Use <code>[xtcz_bollywood_boxoffice]</code> for post, pages and other custom post type</p>
					<p>Use <code>&lt;?php echo do_shortcode('[xtcz_bollywood_boxoffice]'); ?&gt;</code> php code for wordpress theme file</p>
					<p class="notice">Above all settings are not required for bollywood box office. And it works without <strong>Rottentomatoes Key</strong>.</p>
					
			</div>	
			
			<div class="powered_by">
				<h2>Powered by</h2>
				<a target="_blank" href="http://www.xtcz.net/"><img src="<?php echo plugins_url('img/xtcz-bnr.jpg', __FILE__); ?>" /></a>
				<br /><br />
				<a target="_blank" href="http://www.midtb.org/"><img src="<?php echo plugins_url('img/midtb-bnr.jpg', __FILE__); ?>" /></a>
				
			</div>
		</div>
		<?php

}

public function xtcz_register_settings_and_fields()
{
		
		//option_group, option_name
	register_setting('xtcz_plugin_options', 'xtcz_plugin_options');
		
		//id, title of the section,	callback, page?
	add_settings_section('xtcz_main_settings', 'Settings', array($this, 'xtcz_main_settings_cb'), __FILE__); 
		
		//id, title, callback, page
	add_settings_field('xtcz_rottentomatoes_api_token', 'Rottentomatoes Key', array($this, 'xtcz_rottentomatoes_field'), __FILE__, 'xtcz_main_settings');

	add_settings_field('xtcz_theme', 'Theme', array($this, 'xtcz_theme_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_number_of_movies', 'Limit', array($this, 'xtcz_numberofmovies_field'), __FILE__, 'xtcz_main_settings');

	add_settings_field('xtcz_show_release_dates', 'Release Dates', array($this, 'xtcz_show_release_dates_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_show_audience_score', 'Audience score', array($this, 'xtcz_show_audience_score_field'), __FILE__, 'xtcz_main_settings');

	add_settings_field('xtcz_show_img', 'Thumbnail Image', array($this, 'xtcz_showimg_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_img_width', 'Thumbnail Image Width', array($this, 'xtcz_imgwidth_field'), __FILE__, 'xtcz_main_settings');

	add_settings_field('xtcz_show_mpaa_rating', 'Mpaa Rating', array($this, 'xtcz_show_mpaa_rating_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_show_runtime', 'Runtime', array($this, 'xtcz_show_runtime_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_show_cast', 'Cast', array($this, 'xtcz_show_cast_field'), __FILE__, 'xtcz_main_settings');
	add_settings_field('xtcz_show_synopsis', 'Synopsis', array($this, 'xtcz_show_synopsis_field'), __FILE__, 'xtcz_main_settings');


}

public function xtcz_main_settings_cb()
{
		//optional
}


	/*
 * Inputs
 */
public function xtcz_rottentomatoes_field()
{
	$rottentomatoes_key = (isset($this->options['rottentomatoes_key'])) ? $this->options['rottentomatoes_key'] : '';
	echo "<input id='xtcz_plugin_options-rottentomatoes_key' name='xtcz_plugin_options[rottentomatoes_key]' type='text' class='regular-text' value='{$rottentomatoes_key}' />";
	echo '<p id="tagline-description" class="description">Rottentomatoes Key is required to work the plugin. Register <a target="_blank" href="http://developer.rottentomatoes.com/member/register">Rottentomatoes key from here</a></p>';
}

public function xtcz_numberofmovies_field()
{
	$limit = (isset($this->options['limit'])) ? $this->options['limit'] : '';
	echo "<input id='xtcz_plugin_options-limit' name='xtcz_plugin_options[limit]' type='text' class='regular-text' value='{$limit}' />";
	echo '<p id="tagline-description" class="description">Number of movies to show, Maximum is 50.</p>';
}

public function xtcz_show_audience_score_field()
{

	if (isset($this->options['audience_score'])) {
		$showSelected = ($this->options['audience_score']) ? 'checked' : '';
		$hideSelected = ($this->options['audience_score']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-audience_score-true' name='xtcz_plugin_options[audience_score]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-audience_score-false' name='xtcz_plugin_options[audience_score]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_show_mpaa_rating_field()
{

	if (isset($this->options['mpaa_rating'])) {
		$showSelected = ($this->options['mpaa_rating']) ? 'checked' : '';
		$hideSelected = ($this->options['mpaa_rating']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-mpaa_rating-true' name='xtcz_plugin_options[mpaa_rating]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-mpaa_rating-false' name='xtcz_plugin_options[mpaa_rating]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_show_release_dates_field()
{

	if (isset($this->options['release_dates'])) {
		$showSelected = ($this->options['release_dates']) ? 'checked' : '';
		$hideSelected = ($this->options['release_dates']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-release_dates-true' name='xtcz_plugin_options[release_dates]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-release_dates-false' name='xtcz_plugin_options[release_dates]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_show_runtime_field()
{

	if (isset($this->options['runtime'])) {
		$showSelected = ($this->options['runtime']) ? 'checked' : '';
		$hideSelected = ($this->options['runtime']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-runtime-true' name='xtcz_plugin_options[runtime]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-runtime-false' name='xtcz_plugin_options[runtime]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_show_cast_field()
{

	if (isset($this->options['cast'])) {
		$showSelected = ($this->options['cast']) ? 'checked' : '';
		$hideSelected = ($this->options['cast']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-cast-true' name='xtcz_plugin_options[cast]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-cast-false' name='xtcz_plugin_options[cast]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_show_synopsis_field()
{

	if (isset($this->options['synopsis'])) {
		$showSelected = ($this->options['synopsis']) ? 'checked' : '';
		$hideSelected = ($this->options['synopsis']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-synopsis-true' name='xtcz_plugin_options[synopsis]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-synopsis-false' name='xtcz_plugin_options[synopsis]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}

public function xtcz_theme_field()
{

	if (isset($this->options['theme'])) {
		$showSelected = ($this->options['theme'] == 'fullview') ? 'checked' : '';
		$hideSelected = ($this->options['theme'] == 'standard') ? 'checked' : '';
	} else {
		$showSelected = '';
		$hideSelected = 'checked';
	}

	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-theme-standard' name='xtcz_plugin_options[theme]' {$hideSelected} type='radio' class='' value='standard' /> Standard View </label>";
	echo "<label><input id='xtcz_plugin_options-theme-fullview' name='xtcz_plugin_options[theme]' {$showSelected} type='radio' value='fullview' /> Full View</label>";
}

public function xtcz_showimg_field()
{

	if (isset($this->options['imgshow'])) {
		$showSelected = ($this->options['imgshow']) ? 'checked' : '';
		$hideSelected = ($this->options['imgshow']) ? '' : 'checked';
	} else {
		$showSelected = 'checked';
		$hideSelected = '';
	}
	echo "<label style='margin-right:20px'><input id='xtcz_plugin_options-imgshow-true' name='xtcz_plugin_options[imgshow]' {$showSelected} type='radio' value='1' /> Show</label>";
	echo "<label><input id='xtcz_plugin_options-imgshow-false' name='xtcz_plugin_options[imgshow]' {$hideSelected} type='radio' class='' value='0' /> Hide</label>";
}



public function xtcz_imgwidth_field()
{
	$img_width = (isset($this->options['img_width'])) ? $this->options['img_width'] : '';
	echo "<input id='xtcz_plugin_options-img_width' name='xtcz_plugin_options[img_width]' type='text' class='regular-text' value='{$img_width}' />";
}
}

add_action('admin_menu', function () {
	Xtcz_TopBoxOffice_Options::xtcz_add_menu_admin();
});

add_action('admin_init', function () {
	new Xtcz_TopBoxOffice_Options();
});

function xtcz_register_styles()
{
	wp_enqueue_style('xtcz_style', plugins_url('xtcz-style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'xtcz_register_styles');


function xtcz_register_admin_script($hook)
{
	wp_enqueue_script('xtcz_admin_script', plugins_url('xtcz-scrpt.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'xtcz_register_admin_script');

function noBoxofficeResultFound()
{
	echo "Oops, No updated list found";
}

require(dirname(__FILE__) . '/xtcz-shortcode.php');
require(dirname(__FILE__) . '/xtcz-widgets.php');
require(dirname(__FILE__) . '/xtcz-bollywood-shortcode.php');
require(dirname(__FILE__) . '/xtcz-widgets-bollywood.php');