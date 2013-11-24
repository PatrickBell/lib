<div id="content" class="a2z">	
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	<% include AlertBox %>
	$Content
	<h4 class="indexA2Z">$AtoZIndex</h4>
	<% cached 'menu', Aggregate(Page).Max(LastEdited) %>
        <div class="a2zArea">
	        <% control GenerateAtoZ %>
	            <% if NewLetter = 1 %>
	                <% if First %>
	                <% else %>
	                	</ul>
	                	<a class="back2top" href="#content">Back to top &uarr;</a>
	                <% end_if %>
	                <hr />
	                <h3>$AtoZLetter<a name="$AtoZLetter"></a></h3>
	                <ul>
	            <% end_if %>
			    <li><a href="$AtoZLink" title="$AtoZTitle">$AtoZTitle</a></li>
		    <% end_control %>
			</ul>
	    </div>
	<% end_cached %>	    		
</div>
<% include SideBar %>

