<!DOCTYPE html>
<html>
<head>
	<title>fancyBox - Fancy jQuery Lightbox Alternative | Demonstration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<!-- Add jQuery library -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/fancybox/jquery-1.10.1.min.js"></script>

	
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />

	
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();
		


		});
	</script>
	
</head>
<body>
	<h1>fancyBox</h1>

	<p>This is a demonstration. More information and examples: <a href="http://fancyapps.com/fancybox/">www.fancyapps.com/fancybox/</a></p>

	<h3>Simple image gallery</h3>
	<p>
		<a class="fancybox" href="1_b.jpg" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet"><img src="1_s.jpg" alt="" /></a>

		<a class="fancybox" href="2_b.jpg" data-fancybox-group="gallery" title="Etiam quis mi eu elit temp"><img src="2_s.jpg" alt="" /></a>

		<a class="fancybox" href="3_b.jpg" data-fancybox-group="gallery" title="Cras neque mi, semper leon"><img src="3_s.jpg" alt="" /></a>

		<a class="fancybox" href="4_b.jpg" data-fancybox-group="gallery" title="Sed vel sapien vel sem uno"><img src="4_s.jpg" alt="" /></a>
	</p>

	<h3>Different effects</h3>
	<p>
		<a class="fancybox-effects-a" href="5_b.jpg" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="5_s.jpg" alt="" /></a>

		<a class="fancybox-effects-b" href="5_b.jpg" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="5_s.jpg" alt="" /></a>

		<a class="fancybox-effects-c" href="5_b.jpg" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="5_s.jpg" alt="" /></a>

		<a class="fancybox-effects-d" href="5_b.jpg" title="Lorem ipsum dolor sit amet, consectetur adipiscing elit"><img src="5_s.jpg" alt="" /></a>
	</p>

	<h3>Various types</h3>
	<p>
		fancyBox will try to guess content type from href attribute but you can specify it directly by adding classname (fancybox.image, fancybox.iframe, etc).
	</p>
	<ul>
		<li><a class="fancybox" href="#inline1" title="Lorem ipsum dolor sit amet">Inline</a></li>
		<li><a class="fancybox fancybox.ajax" href="ajax.txt">Ajax</a></li>
		<li><a class="fancybox fancybox.iframe" href="<?=site_url('detalle_pedido/listar/add');?>">Iframe</a></li>
		<li><a class="fancybox" href="http://www.google.com/perform.swf">Click Me!</a></li>
		<li><a class="fancybox" href="http://www.adobe.com/jp/events/cs3_web_edition_tour/swfs/perform.swf">Swf</a></li>
	</ul>

	<div id="inline1" style="width:400px;display: none;">
		<h3>Etiam quis mi eu elit</h3>
		<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis mi eu elit tempor facilisis id et neque. Nulla sit amet sem sapien. Vestibulum imperdiet porta ante ac ornare. Nulla et lorem eu nibh adipiscing ultricies nec at lacus. Cras laoreet ultricies sem, at blandit mi eleifend aliquam. Nunc enim ipsum, vehicula non pretium varius, cursus ac tortor. Vivamus fringilla congue laoreet. Quisque ultrices sodales orci, quis rhoncus justo auctor in. Phasellus dui eros, bibendum eu feugiat ornare, faucibus eu mi. Nunc aliquet tempus sem, id aliquam diam varius ac. Maecenas nisl nunc, molestie vitae eleifend vel, iaculis sed magna. Aenean tempus lacus vitae orci posuere porttitor eget non felis. Donec lectus elit, aliquam nec eleifend sit amet, vestibulum sed nunc.
		</p>
	</div>

	<p>
		Ajax example will not run from your local computer and requires a server to run.
	</p>


</body>
</html>