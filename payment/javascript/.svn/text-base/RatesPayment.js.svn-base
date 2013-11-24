//Embed Valuation validation DIV after Valuation Number
jQuery('#ReferenceNumber').after($('#ReferenceValidation').append('<div>'));
jQuery('#Form_PaymentForm_action_RedirectToPaymentPage').after($('#TermsAndConditionsValidation').append('<div>'));

//Embed NextButton
jQuery('#ReferenceNumber').after($('#NextButton').append('<div>'));
// jQuery('#NextButton').html('<input class="action" onclick="validatevid(0);" value="Next" type="submit">');

// Set Convenience Fee and Total Amount fields to read only and visible
jQuery('#Form_PaymentForm_ConvenienceFee').attr('readonly', true);
jQuery('#Form_PaymentForm_TotalAmount').attr('readonly', true);

// Make fields invisible
jQuery('#FirstName').hide();
jQuery('#LastName').hide();
jQuery('#Email').hide();
jQuery('#PhoneNumber').hide();
jQuery('#Amount').hide();
jQuery('#AgreeToConditions').hide();
jQuery('.Actions').hide();

// Clear Javascript message from TotalAmount
if (isNaN(parseFloat(document.getElementById('Form_PaymentForm_TotalAmount').value))) {
    document.getElementById('Form_PaymentForm_TotalAmount').value = '';
}

// Disable Submit Button
// jQuery('#Form_PaymentForm_action_RedirectToPaymentPage').attr('disabled', true);
jQuery('#Form_PaymentForm_action_RedirectToPaymentPage').click(function(event) {
	event.preventDefault();
    return submitbuttonprecheck(event);
});

//Inject span into field container for inline error messages
jQuery('#Form_PaymentForm_ReferenceNumber').after($('#ReferenceValidation').append('<div>'));

// Initial validation for prepopulated Valuation Number field.
validatevid(10);

// Function to calculate Convenience Fee and Total Amount
function calcfee() {
    var amount = parseFloat(document.getElementById('Form_PaymentForm_Amount').value);
    var confee = parseFloat(Math.round(Math.max(amount * 0.02, 3) * 100) / 100);
    if (isNaN(amount) || amount == 0) { amount = 0; confee = 0; }
    var totala = parseFloat(amount + confee);
    document.getElementById('Form_PaymentForm_ConvenienceFee').value = confee.toFixed(2);
    document.getElementById('Form_PaymentForm_TotalAmount').value = totala.toFixed(2);    
}

// Calculate Convenience Fee everytime Amount is changed
jQuery('#Form_PaymentForm_Amount').keyup(function() {
    calcfee();
//    submitbuttonstate();
});

// Validate Valuation Number when enter is pressed
jQuery('#Form_PaymentForm_ReferenceNumber').keyup(function(e) {
    if (e.which == 13) {
        validatevid(10);
    }
});

// Monitor Terms and Conditions checkbox
jQuery('#Form_PaymentForm_AgreeToConditions').change(function() {
//    submitbuttonstate();
});

// Clear Reference Number and show help text 
function clearreferencenumber() {
    document.getElementById('Form_PaymentForm_ReferenceNumber').value = '';
    jQuery('#ReferenceValidation').removeClass();
    jQuery('#ReferenceValidation').addClass('validate-correct');
    jQuery('#ReferenceValidation').html('<p>Enter the Valuation Number of the property you wish to pay rates for without any spaces or hyphens. The number can be found on the top right of your rates letter or through an online search.<br/><a href="/property/rates/rate-record-search/" target="_blank">Online property search</a><br/><a href="/contact-us/#Rates" target="_blank">Contact Us</a></p>');
    document.getElementById('Form_PaymentForm_ReferenceValid').value = 0;
    jQuery('#NextButton').html('<input class="action" onclick="validatevid(0);" value="Retry" type="submit">');
    jQuery('#Form_PaymentForm_ReferenceNumber').attr('readonly', false);
//    document.getElementById('Form_PaymentForm_TotalAmount').value = totala.toFixed(2);
}

function submitbuttonprecheck(event) {
    var boxchecked = (jQuery("#Form_PaymentForm_AgreeToConditions:checked").val()) ? 1 : 0;
    var amount = parseFloat(document.getElementById('Form_PaymentForm_Amount').value);
    var ReferenceValid = document.getElementById('Form_PaymentForm_ReferenceValid').value;

    var validationmessage = '';

    if (ReferenceValid == 0) {
        validatevid(0);
    }

    if (amount < 1 || boxchecked == 0) {
        jQuery('#TermsAndConditionsValidation').html(validationmessage);
        jQuery('#TermsAndConditionsValidation').addClass('validate-incorrect');
    } else {
        jQuery('#TermsAndConditionsValidation').html('');
        jQuery('#TermsAndConditionsValidation').removeClass();
    }

    if ($('Form_PaymentForm').validate() && boxchecked == 1 && amount > 0 && ReferenceValid == 1) {
        //Enable Submit Button
        jQuery('#Form_PaymentForm_action_RedirectToPaymentPage').attr('name', 'action_RedirectToPaymentPage');
		jQuery('#Form_PaymentForm').trigger('submit');
        return true;
    } else {
        jQuery('#Form_PaymentForm_action_RedirectToPaymentPage').attr('name', '');
		return false;
    }
}


// Validate Valuation Number using XML call to NCS server. If property is rateable then rate Amount will be populated with next payment and fees will be calculated.   
function validatevid(fieldcheck){
    str = document.getElementById('Form_PaymentForm_ReferenceNumber').value;

    if (str.length < fieldcheck) {
        jQuery('#ReferenceValidation').removeClass();
        jQuery('#NextButton').html('<input class="action" onclick="validatevid(0);" value="Next" type="submit">');
        jQuery('#ReferenceValidation').html('');
        return;
    }

    var xmlHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
    
    var url = 'payment/code/xmlrelay.php?t=rates&id=' + str;
    xmlHttp.open('GET', url, false);
    xmlHttp.send();
    xmlDoc = xmlHttp.responseXML;
//    alert(xmlDoc.xml);
    xmlResult = xmlDoc.getElementsByTagName('Result')[0].firstChild.nodeValue;

    if (xmlResult == 1) {
        xmlRateable = xmlDoc.getElementsByTagName('Rateable')[0].firstChild.nodeValue;
        xmlLocation = xmlDoc.getElementsByTagName('Location')[0].firstChild.nodeValue;
		// Did see any usage of this variable, xmlDoc.getElementsByTagName('Instalment')[0] firstChild might not exist, in which case, bread the js execution
		// We need to comment out this.
 		// xmlInstalment = xmlDoc.getElementsByTagName('Instalment')[0].firstChild.nodeValue;
        if (xmlRateable == 1) {
            jQuery('#ReferenceValidation').removeClass();
            jQuery('#ReferenceValidation').addClass('validate-correct');
            jQuery('#ReferenceValidation').html('Property Address: <strong>' + xmlLocation + '</strong>');
            
//  Make fields visible
            jQuery('#FirstName').fadeIn('slow');
            jQuery('#LastName').fadeIn('slow');
            jQuery('#Email').fadeIn('slow');
            jQuery('#PhoneNumber').fadeIn('slow');
            jQuery('#Amount').fadeIn('slow');
            jQuery('#ConvenienceFee').fadeIn('slow');
            jQuery('#TotalAmount').fadeIn('slow');
            jQuery('#AgreeToConditions').fadeIn('slow');
            jQuery('.Actions').fadeIn('slow');
            
            document.getElementById('Form_PaymentForm_ReferenceValid').value = 1;
            jQuery('#NextButton').html('<input class="action" onclick="clearreferencenumber();" value="Clear" type="button">');
            jQuery('#Form_PaymentForm_ReferenceNumber').attr('readonly', true);
            jQuery('#Form_PaymentForm_FirstName').focus();
//            submitbuttonstate();
        } else {
            jQuery('#ReferenceValidation').removeClass();
            jQuery('#ReferenceValidation').addClass('validate-incorrect');
            jQuery('#ReferenceValidation').html('<strong>Property Not Rateable:</strong> ' + xmlLocation + '<br/><a href="/property/rates/rate-record-search/" target="_blank">Online property search</a><br/><a href="/contact-us/#Rates" target="_blank">Contact Us</a>');
            document.getElementById('Form_PaymentForm_ReferenceValid').value = 0;
            jQuery('#NextButton').html('<input class="action" onclick="validatevid(0);" value="Retry" type="submit">');
//            submitbuttonstate();
        }
    } else {
        jQuery('#ReferenceValidation').removeClass();
        jQuery('#ReferenceValidation').addClass('validate-incorrect');
        jQuery('#ReferenceValidation').html('<strong>No Matching Record Found</strong><br/>Please enter the Valuation Number of the property you wish to pay rates for without any spaces or hyphens. The number can be found on the top right of your rates letter or through an online search.<br/><a href="/property/rates/rate-record-search/" target="_blank">Online property search</a><br/><a href="/contact-us/#Rates" target="_blank">Contact Us</a>');
        document.getElementById('Form_PaymentForm_ReferenceValid').value = 0;
        jQuery('#NextButton').html('<input class="action" onclick="validatevid(0);" value="Retry" type="submit">');
//        submitbuttonstate();
    }
    return;
}
