<div class="typography">
	<% include SideBar %>
	<div id="content" class="<% if Menu(2) %>has-sidebar<% end_if %>">
			
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
		<h1>$Title</h1>
		
		$TopContent
		
		<div id='GoogleMapCanvas'></div>
		
		<div class="column six links">
			<div class="module">
				$Description
			</div>
		</div>
		
		<div class="column six links">
			<div class="module">
				$Legend
			</div>
		</div>
		
		<div class="clear">&nbsp;</div>
		
		$Content
		$Form
		$PageComments
		
		<% if GeotaggedChildren %>
			<h4>Sites</h4>
			<ul>
			<% control GeotaggedChildren %>
				<li><a href="$Link">$Title</a></li>
			<% end_control %>
			</ul>
		<% end_if %>

	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>