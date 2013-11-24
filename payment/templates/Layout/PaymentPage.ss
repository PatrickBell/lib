<div class="typography"> 
		<div id="content">
			
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
	    <% include AlertBox %>
        
	        <% if PageMode == "rates" %>
                <% include RatesPayment %>
                <% include FeeNotice %>
	        <% else_if PageMode == "water" %>
	            <% include WaterPayment %>
                <% include FeeNotice %>
	        <% else_if PageMode == "confirm" %>
	            <% include ConfirmPayment %>
	        <% else %>
                <% include PaymentsStart %>
	        <% end_if %>
	       
<style>
    .validate-correct p {
	    color: #000000;
	    line-height: 150%;
	    margin-top: 4px;
        }
        
    .validate-incorrect p {
	    color: #000000;
	    line-height: 150%;
	    margin-top: 4px;
        }
    .validate-incorrect strong {
        color: #EE0000;
    }
        
    #ConvenienceFee, #TotalAmount {
        display: none;
    }

</style>
        
		</div>
	
	    <% include SideBar %>
</div>
