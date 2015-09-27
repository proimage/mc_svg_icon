MC SVG Icon is a simple plugin to enable easy use of external SVG icons in EE2.


# Usage #
========================================

	## Standalone (no index.php changes) ##
	--------------------------------------------------

	`{exp:svg:single:[full_symbol_ID] file="/relative/path/to/svgdefs.svg"}`

	For example:

	`{exp:svg:single:icon-home file="/assets/svg/svgdefs.svg"}`


	## Single tag: ##
	--------------------------------------------------

	`{exp:svg:single:[symbol_ID]}`

	Where `[symbol_ID]` is the ID of the desired `<symbol>`, sans whatever you specified for 'icon_svg_symbol_id_prefix' in the index.php. So to show the home icon, use `{exp:svg:single:home}`


	## Tag Pair: ##
	--------------------------------------------------

	`{exp:svg:pair}[symbol_ID]{/exp:svg:pair}`



# Parameters #
========================================


	## `class="myclass"` ##
	--------------------------------------------------
	Adds the specified class(es) to the generated <svg> tag.


	## `title="My Title"` ##
	--------------------------------------------------
	Adds a <title> tag to the generated <svg> and links it to the <use> tag using aria-labelledby.


	## `file="/relative/path/to/svgdefs.svg"` ##
	--------------------------------------------------
	The .svg file to use for this instance. It can either be used as an override of the regular file specified in index.php, or as a way of avoiding alterations to index.php altogether.

	The path should be relative to the web root, as it will be used by the browser to find the external .svg file.


# Config vars in index.php (optional; recommended) #
========================================

Add to index.php (or your equivalent) the following:

	$assign_to_config['icon_svg_file'] = '/relative/path/to/svgdefs.svg';
	$assign_to_config['icon_svg_symbol_id_prefix'] = 'icon-';
	$assign_to_config['icon_svg_global_class'] = 'icon';

(The prefix is what you see in the 'id' parameter on line 3 below)


# Sample svgdefs.svg #
========================================

	1:	<svg display="none" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	2:		<defs>
	3:			<symbol id="icon-home" viewBox="0 0 1024 1024">
	4:				<title>home</title>
	5:				<path class="path1" d="..."></path>
	6:			</symbol>
	7:			<!-- more <symbols> -->
	8:		</defs>
	9:	</svg>


# Sample Output #
========================================

Assuming the following settings in index.php:

	$assign_to_config['icon_svg_file'] = '/assets/svg/svgdefs.svg';
	$assign_to_config['icon_svg_global_class'] = 'icon';
	$assign_to_config['icon_svg_symbol_id_prefix'] = 'icon-';

The template code `{exp:svg:single:home class="myclass"}` would produce:

	1:	<svg class="icon icon-home myclass">
	2:		<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/assets/svg/svgdefs.svg#icon-home"></use>
	3:	</svg>

By adding a title parameter, the code `{exp:svg:single:home class="myclass" title="My Title"}` would produce:

	1:	<svg class="icon icon-home myclass">
	2:		<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/assets/svg/svgdefs.svg#icon-home" aria-labelledby="title__home">
	3:  	 	<title id="title__home">My Title</title>
	4:		</use>
	5:	</svg>