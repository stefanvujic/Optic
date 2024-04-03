<?
include_once "_inc_global.php";
include_once "_inc_header.php";
?>
<STYLE>
a{color:#000;}
a:hover{color:#000;}
.ul1 { padding-top:4px; }
</STYLE>
</HEAD>
<BODY>
<center>
<br>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0" style="font-size:24px;letter-spacing:1px;">
<tr><td>

<UL>

<li><b>Quick Tips:</b>
	<ul class=ul1>
	  <li>To switch between layers, click the current layer thumbnail at the top of the right side menu.</li>
	  <li>The padlock locks/unlocks the z-index (layer stacking).</li>
	  <li>The order of the thumbnails reflects the z-index so when the z-index is unlocked and you click a thumbnail it's position automatically changes (it becomes the top layer).</li>
	  <li>When the login/logout button is half red and half green this means you are logged in but do not have update rights to the current folder.</li>
	  <li>To hide ALL menus doubleClick the red arrow in the top right corner. To restore the menus, mouseOver the top right corner and doubleClick the green arrow.</li>
	  <li>If you find the buttons in the top toolbar are 'jumbled' try setting your browser's ZOOM to 90% or even 80% for this site.</li>
	  <li>Double Click a layer to expand/contract it's size.</li>
	</ul>
</li>

 <br>&nbsp;
	
<li><b>Shortcut Keys:</b>
	<ul class=ul1>
	  <li>Use the arrow keys to rotate the tiling of the current layer.</li>
	  <li>Use the arrow keys + the Control key to fine adjust the current mouse mode setting.</li>
	  <li>Control/s will open the "Save View" box.</li>
	  <li>Pressing Control and a z-index button(s) changes the layer's z-index by +1 (or -1).</li>
	  <li>To apply mouse events to the current layer (regardless of where you click) press and hold the Control key.</li>
	</ul>
</li>
	

 <a id="animation">	
 <br>&nbsp;
 <li><b>Animation Options:</b>
  		<ul class=ul1>
			<!-- <li>Relative : When running a saved action, the changes are applied relative to the starting point.<br>&nbsp;</li> -->
			<li>Merge : Different types of changes are applied sequentially when 'Merge' is OFF or in parrellel when 'Merge' is ON.</li>
		</ul>
 </li>
 </a>
 
 <a id="camera">	
 <br>&nbsp;
 <li><b>Using the Camera:</b>
  		<ul class=ul1>
			<li>Click the camera icon to create an image from the current picture. 
				This will be saved as a .png image and added to your current folder.</li>
			<li>The shape of the image (width and height ratio) will be the same as the current picture.</li>
			<li>To change the dimensions of the image you can either change the shape of the browser, or drag the 
				yellow arrow icon (top right corner of the left menu/thumbnails) to the left or right. This will 
				change the picture shape.</li>
		</ul>
 </li>
 </a>
 
 <a id="openview">	
 <br>&nbsp;
 <li><b>Opening a View:</b>
  		<ul class=ul1>
			<li>The image is generated from a View that is loaded without any toolbars showing. This is the same as 
				right clicking a View and selecting 'open' which opens the view in a new tab in your browser. 
				Although no toolbars are visible certain actions are still available. These are as follows:
  				<ul class=ul1>
					<li>Arrow keys (Left/Right/Up/Down) 'Turns' the picture.</li>
					<li>Arrow keys + Ctrl 'Warps' the picture.</li>
					<li>Arrow keys + Shift 'Folds' the picture.</li>
					<li>Arrow keys + Ctrl + Shift 'Skews' the picture.</li>
				</ul>
			</li>
		</ul>
 </li>
 </a>
 
 
 <a id="tiling">	
 <br>&nbsp;
 <li><b>Tiling Tips:</b>
  		<ul class=ul1>
			<li>???? : <br>&nbsp;</li>
		</ul>
  </li>
	
 <a id="viewsettings">	
 <br>&nbsp;
 <li><b>View Settings:</b> These are applied to ALL layers within the current View.
 		<ul class=ul1>
			<li>Gradient Background : When a gradient is applied to the background 
			    and the view is zoomed out, blending of individual layers with the 
				view background is disabled. Blending with other layers still works.</li>
		</ul>
  </li>
 </a>

	
 <a id="sizesettings">	
 <br>&nbsp;
 <li><b>Size Settings:</b> 
 		<ul class=ul1>
			<li>Natural Size : When this button is clicked the layer size ratio (width and height) is changed to match the 
			actual size ratio of the image. This can be adjusted so that the 'natural'
			size is still kept by using the 'Size' slider to adjust both the X and Y axis at the same time.</li>
			<li>Fix Ratios : When turned on it maintains the width/height ratio regardless of the 
			    window shape unless it is in fullscreen mode. This setting applies to ALL layers in the View. </li>
			<li>Lock Position : When turned on it locks the position of the top/left corner when resizing the layer. </li>
		</ul>
  </li>
 </a>
	
 <a id="filters">	
 <br>&nbsp;
 <li><b>Filters:</b> A filter is a collection of settings. 
 		<ul class=ul1>
			<li>Clicking the 'Save Filter' button will create a filter using all the settings of the current layer and save it to your current folder.</li>
			<li>Clicking an existing filter will apply it's settings to the current layer.</li>
			<li>A filter does not include Size and Position settings and does not change the Image.</li>
		</ul>
  </li>
 </a>


 <a id="warp">	
 <br>&nbsp;
 <li><b>Warp options:</b> These are used to control the effect of the Warp (mainly for when when zoomed in).
 		<ul class=ul1>
			<li>Global 	: the entire screen is warped  (default ON).</li>
			<li>Offset Edge : it warps from the edge or the center - it only takes effect if there are 4+ panels (default OFF).</li>
		</ul>
 </li>
 </a>
	
 <a id="colors">	
 <br>&nbsp;
 <li><b>Colors:</b> Items in this category include both CSS and Pixel manipulations.
 		<ul class=ul1>
			<li>CSS 	: "Blend" and "Colors".</li>
			<li>Pixel	: Everything else.</li>
			<li>Pixel Order	: The Pixel manipulations are applied in the same order as they appear 
							  on the menu. If multiple items are enabled this can cause the system to slow down.</li>
			<li>Some items can be overridden by later operations (for example the Opacity setting in 
				"Swap Colors" will be overridden by the Opacity setting in "Fill Color").</li>
		</ul>
 </li>
 </a>

 <a id="swapcolors">	
 <br>&nbsp;
 <li><b>Swap Colors:</b> These are used to swap one color for another.
 		<ul class=ul1>
			<li>Tolerance 	: This determines how exact the color match must be.</li>
			<li>Picker Tool	: Click to select the 'From' color. Double click to open a window with the original image
			loaded (click a color to choose it). Clicking another image in the thumbnail list allows a color to 
			be picked from any image.</li>
		</ul>
 </li>
 </a>

 <a id="swappixels">	
 <br>&nbsp;
 <li><b>Swap Pixels:</b> These are used to swap one color component from the top image with another 
 				  from the underlying image. 
 		<ul class=ul1>
			<li>If there is no underlying image then this setting will have no effect.</li>
			<li>If the underlying image is a different size the effect is distorted.</li>
      		<li>At least one of "red, green or blue" must be selected, but not all three.</li>
			<li>The swap is applied to every pixel in the top image.</li>
		</ul>
 </li>
 </a>


 <a id="fillcolor">	
 <br>&nbsp;
 <li><b>Color Fill:</b> This is used to fill an area with a new color.
		<ul class=ul1>
			<li>Tolerance 	: This determines how exact the color match must be.</li>
			<li>Picker Tool	: Click to select the area to be filled.</li>
			<li>Please Note: This gets applied to the X/Y coordinates of a particular location
			on the LAYER which is not necessarily always the same location on the image.
			To 'lock it in' take a snapshot of the layer (see the small camera icon)
			and then use the new image thereafter.</li>
		</ul>
 </li>
 </a>
	
 <a id="shadows">
 <br>&nbsp;
 <li><b>Shadows:</b>
 		<ul class=ul1>
			<li>Frame vs Image	: </li>
			<li>Multiple Shadows: </li>
			<li></li>
		</ul>
</li>
 </a>
	
	
</ul>

</td></tr>
</TABLE>
</center>

</BODY></HTML>
