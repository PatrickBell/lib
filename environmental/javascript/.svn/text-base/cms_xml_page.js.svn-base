jQuery(function($) {
	$('#Form_EditForm_SiteType').live('change', function() {
		// Update the site names field if available
		if ($('#Form_EditForm_SiteID').length) {
			// Give visual cues to show it's loading
			$('#Form_EditForm_SiteID option').remove();
			$('#Form_EditForm_SiteID').append($('<option>Loading ...</option>'));
			$('#Form_EditForm_SiteID').parent().append($("<div class='loading'>&nbsp;</div>"));
		
			// Get the names for this site type via ajax
			var url = $('#Form_EditForm_getsitenames').val()+$(this).val();
			$.ajax({
				url: url,
	   			success: function(data){
	     			// Replace the options with the new set
					$('#Form_EditForm_SiteID option').remove();
				
					$(data).find('option').each(function() {
						$('#Form_EditForm_SiteID').append($(this));
					});
				
					// Highlight the default value
					$('#Form_EditForm_SiteID').val(0);
	   			},
				complete: function(data){
					$('#Form_EditForm div#SiteID div.loading').remove();
				}
			});
		}
	});
});
