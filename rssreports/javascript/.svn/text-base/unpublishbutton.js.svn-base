// Add popup to Unpublish box when mouse enters div containing action buttons. This is bound via live as the action buttons can be rebuilt with AJAX.
jQuery('#form_actions_right').live('mouseenter', function() {

    //Check if Unpublish button needs to be disabled
    if (jQuery('#Form_EditForm_action_unpublish').attr('name') != '') {

        //Disable Unpublish Button
        jQuery('#Form_EditForm_action_unpublish').attr('name', '');

        //Bind Mousedown event handler to Unpublish button. Mouseover is used instead of click to avoid conflict 
        jQuery('#Form_EditForm_action_unpublish').mousedown(function() {
            //Display confirmation box if there is mousedown on Unpublish button
            confirm_box = confirm("Unpublishing this page will also unpublish all of it's child pages");
            //If OK is clicked on confirmation box enable button and resubmit
            if (confirm_box == true) {
                //Enable Unpublish Button
                jQuery('#Form_EditForm_action_unpublish').attr('name', 'action_unpublish');
                //Trigger click on Unpublish button to set submit in motion
                jQuery('#Form_EditForm_action_unpublish').click();
                return true;
            }
        });

    }

    //Check if Delete button needs to be disabled
    if (jQuery('#Form_EditForm_action_delete').attr('name') != '') {

        //Disable Delete Button
        jQuery('#Form_EditForm_action_delete').attr('name', '');

        //Bind Mousedown event handler to Delete button. Mouseover is used instead of click to avoid conflict 
        jQuery('#Form_EditForm_action_delete').mousedown(function() {
            //Display confirmation box if there is mousedown on Delete button
            confirm_box = confirm("Are you sure you want to Delete this page?");
            //If OK is clicked on confirmation box enable button and resubmit
            if (confirm_box == true) {
                //Enable Delete Button
                jQuery('#Form_EditForm_action_delete').attr('name', 'action_delete');
                //Trigger click on Delete button to set submit in motion
                jQuery('#Form_EditForm_action_delete').click();
                return true;
            }
        });
    }
});