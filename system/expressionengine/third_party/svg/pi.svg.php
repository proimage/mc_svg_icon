<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MC SVG Icon Plugin
 *
 * @category    Plugin
 * @author      Michael C.
 * @link        http://www.pro-image.co.il
 */

$plugin_info = array(
	'pi_name'       => 'MC SVG Icons',
	'pi_version'    => '1.0',
	'pi_author'     => 'Michael C.',
	'pi_author_url' => 'http://www.pro-image.co.il',
	'pi_description'=> 'Simple plugin to enable easy use of external SVG icons in EE2.',
	'pi_usage'      => Svg::usage()
);


class Svg {

	// PREPARE VARS
	public $return_data;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();


	// SVG Motherfile
		$file = trim($this->EE->TMPL->fetch_param('file'));

		// Use specified 'file' parameter override
		if ($file)
		{
			$icon_svg_file = $file;
		}
		// Or default to the one specified in the index.php
		elseif($this->EE->config->item('icon_svg_file') !== false)
		{
			$icon_svg_file = $this->EE->config->item('icon_svg_file');
		}
		// Or break with an error
		else
		{
			$this->EE->TMPL->log_item('MC SVG Icon - ERROR: You\'re missing the required "icon_svg_file" variable in your index.php, and you haven\'t specified an alternative using the file="" parameter.');
			return "<!-- MC SVG Icon cannot produce output; Check the template debug log for details. -->";
		}


	// Get actual icon name
		$tag_parts = $this->EE->TMPL->tagparts;
		// If specified as part of the tag, use that.
		if ( is_array( $tag_parts ) && isset( $tag_parts[2] ) )
		{
			$icon_symbol = $tag_parts[2];
		}
		// Otherwise, use the (trimmed) text inside the tag pair.
		elseif ( isset($this->EE->TMPL->tagdata) )
		{
			$icon_symbol = trim($this->EE->TMPL->tagdata);
		}
		// If neither exist, give an error.
		else
		{
			$this->EE->TMPL->log_item('MC SVG Icon - ERROR: No icon specified. You can specify an icon either as the 4th tagpart ({exp:svg:single:___} or as text inside a tag pair {exp:svg:pair}___{/exp:svg:pair}');
			return "<!-- MC SVG Icon cannot produce output; Check the template debug log for details. -->";
		}


	// Global class applied to all generated <svg> tags
		if( $this->EE->config->item('icon_svg_global_class') != FALSE )
		{
			$global_class = $this->EE->config->item('icon_svg_global_class') . ' ';
		}
		else
		{
			$global_class = '';
			$this->EE->TMPL->log_item('MC SVG Icon - Notice: You don\'t have the "icon_svg_global_class" variable in your index.php.');
		}


	// Common ID prefix for icon symbols
		if( $this->EE->config->item('icon_svg_symbol_id_prefix') != FALSE )
		{
			$prefix = $this->EE->config->item('icon_svg_symbol_id_prefix');
		}
		else
		{
			$prefix = '';
			$this->EE->TMPL->log_item('MC SVG Icon - Notice: You don\'t have the "icon_svg_symbol_id_prefix" variable in your index.php.');
		}


	// Get icon class
		$extra_classes = ' ' . $this->EE->TMPL->fetch_param('class');

	// Get icon title
		$title = $this->EE->TMPL->fetch_param('title');


	// Build the output
		$icon_svg = '<svg class="' . $global_class . $prefix . $icon_symbol . $extra_classes . '"><use xlink:href="' . $icon_svg_file . "#" . $prefix . $icon_symbol . '"';
		if ($title)
		{
			$icon_svg .= ' aria-labelledby="title__' . $icon_symbol . '"><title id="title__' . $icon_symbol . '">' . $title . '</title>';
		}
		else
		{
			$icon_svg .= '>';
		}
		$icon_svg .= '</use></svg>';

	// Off it goes!
		return $icon_svg;
	}
	/* END __construct */


	public function single()
	{
		return $this->__construct();
	}

	public function pair()
	{
		return $this->__construct();
	}

	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
MC SVG Icon is a simple plugin to enable easy use of external SVG icons in EE2.


Usage
========================================

  Standalone (no index.php changes)
  --------------------------------------------------

  {exp:svg:single:[full_symbol_ID] file="/relative/path/to/svgdefs.svg"}

  For example:

  {exp:svg:single:icon-home file="/assets/svg/svgdefs.svg"}


  Single tag:
  --------------------------------------------------

  {exp:svg:single:[symbol_ID]}

  Where [symbol_ID] is the ID of the desired <symbol>, sans whatever you specified for 'icon_svg_symbol_id_prefix' in the index.php. So to show the home icon, use {exp:svg:single:home}


  Tag Pair:
  --------------------------------------------------

  {exp:svg:pair}[symbol_ID]{/exp:svg:pair}



Parameters
========================================


  class="myclass"
  --------------------------------------------------
  Adds the specified class(es) to the generated <svg> tag.


  title="My Title"
  --------------------------------------------------
  Adds a <title> tag to the generated <svg> and links it to the <use> tag using aria-labelledby.


  file="/relative/path/to/svgdefs.svg"
  --------------------------------------------------
  The .svg file to use for this instance. It can either be used as an override of the regular file specified in index.php, or as a way of avoiding alterations to index.php altogether.

  The path should be relative to the web root, as it will be used by the browser to find the external .svg file.


Config vars in index.php (optional; recommended)
========================================

Add to index.php (or your equivalent) the following:

$assign_to_config['icon_svg_file'] = '/relative/path/to/svgdefs.svg';
$assign_to_config['icon_svg_symbol_id_prefix'] = 'icon-';
$assign_to_config['icon_svg_global_class'] = 'icon';

(The prefix is what you see in the 'id' parameter on line 3 below)


Sample svgdefs.svg
========================================

  1: <svg display="none" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
  2:   <defs>
  3:     <symbol id="icon-home" viewBox="0 0 1024 1024">
  4:       <title>home</title>
  5:       <path class="path1" d="..."></path>
  6:     </symbol>
  7:     <!-- more <symbols> -->
  8:   </defs>
  9: </svg>

The svgdefs.svg generated by the wonderful IcoMoon.io app (https://icomoon.io/app/) is perfect for this.


Sample Output
========================================

Assuming the following settings in index.php:

$assign_to_config['icon_svg_file'] = '/assets/svg/svgdefs.svg';
$assign_to_config['icon_svg_global_class'] = 'icon';
$assign_to_config['icon_svg_symbol_id_prefix'] = 'icon-';

The template code {exp:svg:single:home class="myclass"} would produce:

  1: <svg class="icon icon-home myclass">
  2:   <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/assets/svg/svgdefs.svg#icon-home"></use>
  3: </svg>

By adding a title parameter, the code {exp:svg:single:home class="myclass" title="My Title"} would produce:

  1: <svg class="icon icon-home myclass">
  2:   <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/assets/svg/svgdefs.svg#icon-home" aria-labelledby="title__home">
  3:     <title id="title__home">My Title</title>
  4:   </use>
  5: </svg>


A Note of Copy-Pasta Warning
========================================

Many lines in this usage text are indented using special em-space characters (since EE strips away any tabs or regular spaces). If you copy these into an EE template, it will translate them into &emsp; entities and you'll merely end up with some extra spacing here and there.

However, if you copy them into a .php file (for example, your index.php), the PHP processor will throw an error... so don't do that.

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.icon.php */
/* Location: /system/expressionengine/third_party/icon/pi.icon.php */
