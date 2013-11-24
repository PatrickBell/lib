jQuery(document).ready(function() {
	var emailthispageContent = null;
	var buttonSelector = "a#emailthispagelink";
	var contentSelector = "#inlineEmailThisPageText";

	jQuery(buttonSelector).fancybox({
		'scrolling': 'no',
		'transitionIn' :'fade',
		'transitionOut' :'fade',
		'titlePosition' : 'inside',
		'padding': 15,
		'onComplete': function() {  //clear form when it is closed and re-opened
			emailthispageContent = jQuery(contentSelector).html();
		},
		'onCleanup': function() {
			jQuery(contentSelector).html(emailthispageContent);
		},
		'onStart': function() {
			attachAjax();   //attach the ajax function to the send button
		}
	});

	function attachAjax() {
		var form = jQuery("#Form_submitEmailThisPage");
		if (form.length == 0) form = jQuery("#Form_homesubmitEmailThisPage");

		form.submit(function(){
			var postURL = jQuery(this).attr('action');
			
			jQuery.ajax({
				type: 'POST',
				url: postURL,
				data: jQuery(this).serialize(),
				success: function(data){
					jQuery(contentSelector).html(data);
				}
			});

			return false;
		});
	}
});