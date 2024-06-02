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
	  <li>The order of the layer thumbnails reflects the z-index so when the z-index is unlocked and you click a thumbnail it's position automatically changes (it becomes the top layer).</li>
	  <li>To hide ALL menus doubleClick the red arrow in the top left corner. To restore the menus, mouseOver the top left corner and doubleClick the green arrow.</li>
	  <li>Double Click a layer to expand/contract it's size.</li>
	  <li>Use the arrow keys to rotate the tiling of the current layer.</li>
	  <li>Use the arrow keys + the Control key to fine adjust the current mouse mode setting.</li>
	  <li>Control/s will open the "Save View" box.</li>
	  <li>Pressing Control and a z-index button changes the layer's z-index by +1 (or -1).</li>
	  <li>To apply mouse events to the current layer (regardless of where you click) press and hold the Control key.</li>
	</ul>
</li>

<br>&nbsp;
<li><b>Buttons and Icons:</b>
	<ul class=ul1>
		<li><img src="_pvt_images/arrow_red.png" style="width:20px;height:20px"> and  
		    <img src="_pvt_images/arrow_green.png" style="width:20px;height:20px"> &nbsp;Show/Hide menus. 
		</li>
		<li><img src="_pvt_images/add_new.png" style="width:20px;height:20px"> &nbsp;  Allows you to upload images, search for videos, etc.</li>
		<li><img src="_pvt_images/home.png" style="width:20px;height:20px"> &nbsp;  Returns to your home folder and Loads your home Page/View.</li>
		<li><img src="_pvt_images/settings.png" style="width:20px;height:20px"> &nbsp;  Loads your settings.</li>
		<li><img src="_pvt_images/profile.png" style="width:20px;height:20px"> &nbsp;  Loads your profile..</li>
		<li><img src="_pvt_images/playleft.png" style="width:20px;height:20px"> and
		    <img src="_pvt_images/play.png" style="width:20px;height:20px"> &nbsp; Load the last/next image into the current layer. 
		 </li>
		<li><img src="_pvt_images/forward.png" style="width:20px;height:20px"> &nbsp; Starts a slideshow in the current layer.</li>
		<li><img src="_pvt_images/mouse.png" style="width:20px;height:20px"> &nbsp; Allows you to select the mode/action for mouse movements on an image (click and drag).</li>
		<li><img src="_pvt_images/addgreen.png" style="width:20px;height:20px"> &nbsp; Allows you to add various types of layers to the current View. </li>
		<li><img src="_pvt_images/color.gif" style="width:20px;height:20px"> &nbsp; Use this to change the background color for the current View.<br>
		Note that if any of the layers are fullscreen the color will not be visible.</li>
		<li><img src="_pvt_images/save1.png" style="width:20px;height:20px"> &nbsp; Save or Save As the current View</li>
		<li><img src="_pvt_images/camera.png" style="width:20px;height:20px"> &nbsp; Take a picture of the current View. The resulting image will be saved to your current folder.</li>
		<li><img src="_pvt_images/reset.png" style="width:20px;height:20px"> &nbsp; Reset the current layer.</li>
		<li><img src="_pvt_images/move_forward.png" style="width:20px;height:20px"> &nbsp; Change the layer stacking order.</li>
		<li><img src="_pvt_images/Lock-Lock.png" style="width:20px;height:20px"> &nbsp; Lock the stacking order. If unlocked then clicking a layer brings it to the top/front.</li>
		<li><img src="_pvt_images/hideOn.png" style="width:20px;height:20px"> &nbsp; Hide the current layer.</li>
		<li><img src="_pvt_images/trashcan.png" style="width:20px;height:20px"> &nbsp; Delete the current layer.</li>
		<li><img src='_pvt_images/delete.png' style='height:14px;width:16px; border:solid 2px #bbddff;border-top:solid 5px #bbddff; cursor:pointer;'> &nbsp; Show/Hide the layer handles. (Only available when the layer is not fullscreen)</li>
		<li><img src="_pvt_images/fixedOff.png" style="width:20px;height:20px"> &nbsp;  Maintain the layer size ratios (height/width) when the window size ratio changes. (Only available when the layer is not fullscreen) </li>
		<li><img src="_pvt_images/naturalOff.png" style="width:20px;height:20px"> &nbsp; Apply the actual (real) image size ratio. To maintain the ratio and reize the layer, use the 'Size' slider. </li>
		<li><img src="_pvt_images/filter.png" style="width:20px;height:20px"> &nbsp; Save the current layer's settings as a Filter. </li>
	</ul>
 </li>
 

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

	
 <a id="sizesettings">	
 <br>&nbsp;
 <li><b>Controlling Layer Size and Shape:</b>
  		<ul class=ul1>
		    <li>The 'Fullscreen' button will expand a layer to always cover 100% of the window. When a layer is in fullscreen mode the size and position handles are not available.</li>
			<li>A layer's size and shape can be adjusted by selecting the 'Size' mouse mode, 
			    or by using the 'Size' sliders under 'Image' settings,
				or by turning on the layer handles and using the mouse to drag the size change button.</li>
			<li>When 'Fixed Ratios' is turned ON the shape and size of the layer will remain unchanged regardless of the shape of the window.</li>
			<li>When 'Fixed Ratios' is turned OFF the shape will always fill the same width and height percentages of the window (UNLESS the layer is set to 'Fullscreen').</li>
			<li>Natural Size : When this button is clicked the layer size ratio (width and height) is changed to match the 
			actual size ratio of the image. This can be adjusted so that the 'natural'
			size is still kept by using the 'Size' slider to adjust both the X and Y axis at the same time.</li>
			<li>Lock Position : When turned on it locks the position of the top/left corner when resizing the layer. </li>
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

	
 
 <a id="tiling">	
 <br>&nbsp;
 <li><b>Tiling Tips:</b>
  		<ul class=ul1>
			<li>???? : <br>&nbsp;</li>
		</ul>
  </li>

 <a id="animation">	
 <br>&nbsp;
 <li><b>Animation Options:</b>
  		<ul class=ul1>
			<li>Replay : As you make changes to the current layers, the changes are saved. Clicking the 'play' button animates these changes. 
			To create a customized 'Action' reset the history, then make the desired changes and then click the Save icon. </li>
			<li>Autoplay : This allows you to animate the current mouse setting within certain limits. These too can be saved as an 'Action'. </li>
			<li>Curve : This applies the changes faster at one extreme and slower at the other. </li>
			<li>Pause : This adjust the time delay between the individual changes. It can be used to adjust the speed. </li>
			<li>Delta : This adjusts the size of each individual change. It too can be used to adjust the speed. </li>
			<li>Merge : This applies different types of changes in parrellel. When 'Merge' is off they are applied sequentially as recorded.</li>
			<li>Smooth : This smooths out the path between recorded changes. </li>
			<li>Animations are only saved with a View if they are playing at the time you save it.</li>
			<li>When running a saved Autoplay action, the changes are applied <i>relative</i> to the starting point.</li>
		</ul>
 </li>
 </a>
 
	
	
</ul>

</td></tr>
</TABLE>
</center>

</BODY></HTML>
