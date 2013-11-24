<?php

class PaymentPageValidator extends RequiredFields {
	/**
	 * Temporarily switch to another locale
	 */
	function php($data) {
		$originalLocale = i18n::get_locale();
		i18n::set_locale('en_GB');
		parent::php($data);
		i18n::set_locale('en_NZ');
	}

	/**
	 * The validation will only be performed on submit.
	 * Add custom validation messages.
	 */
	function includeJavascriptValidation() {
		if($this->getJavascriptValidationHandler() == 'prototype') {
			Requirements::javascript(SAPPHIRE_DIR . "/thirdparty/prototype/prototype.js");
			Requirements::javascript(SAPPHIRE_DIR . "/thirdparty/behaviour/behaviour.js");
			Requirements::javascript(SAPPHIRE_DIR . "/javascript/prototype_improvements.js");
			Requirements::add_i18n_javascript(SAPPHIRE_DIR . '/javascript/lang');
			Requirements::javascript(SAPPHIRE_DIR . "/javascript/Validator.js");
		
			$cleaningCode = '';
			$dataFields = $this->form->Fields()->dataFields();
			if($dataFields) foreach($dataFields as $field) {
				$cleaningCode .= "clearErrorMessage('".$field->Name()."');\n";
			}


			$code = $this->javascript();
			$formID = $this->form->FormName();
			$js = <<<JS
Behaviour.register({
	'#$formID': {
		validate : function(fromAnOnBlur) {
			if (typeof fromAnOnBlur=='undefined') {
				initialiseForm(this, fromAnOnBlur);

				// Clear all fields
				$cleaningCode

				$code

				var el = _CURRENT_FORM.elements['Amount'];
				if(el && el.value && parseFloat(el.value)<1) {
					validationError(el, "Enter an Amount of Rates to Pay that is more than zero.","validation");
		 		}
				if(el && el.value && parseFloat(el.value)>=1000000) {
					validationError(el, "The amount exceeds the allowed maximum.","validation");
		 		}

				var el = _CURRENT_FORM.elements['AgreeToConditions'];
				if(el && !el.checked) {
					validationError(el, "You must agree to the Terms and Conditions to continue.","validation");
		 		}

				var error = hasHadFormError();
				// if(!error && fromAnOnBlur) clearErrorMessage(fromAnOnBlur);
				if(error && !fromAnOnBlur) focusOnFirstErroredField();
				
				return !error;
			}
		},
		onsubmit : function() {
			if(typeof this.bypassValidation == 'undefined' || !this.bypassValidation) return this.validate();
		}
	},
	'#$formID input' : {
		initialise: function() {
			if(!this.old_onblur) this.old_onblur = function() { return true; } 
			if(!this.old_onfocus) this.old_onfocus = function() { return true; } 
		},
		onblur : function() {
			if(this.old_onblur()) {
				// Don't perform instant validation for CalendarDateField fields; it creates usability wierdness.
				if(this.parentNode.className.indexOf('calendardate') == -1 || this.value) {
					return $('$formID').validate(this);
				} else {
					return true;
				}
			}
		}
	},
	'#$formID textarea' : {
		initialise: function() {
			if(!this.old_onblur) this.old_onblur = function() { return true; } 
			if(!this.old_onfocus) this.old_onfocus = function() { return true; } 
		},
		onblur : function() {
			if(this.old_onblur()) {
				return $('$formID').validate(this);
			}
		}
	},
	'#$formID select' : {
		initialise: function() {
			if(!this.old_onblur) this.old_onblur = function() { return true; } 
		},
		onblur : function() {
			if(this.old_onblur()) {
				return $('$formID').validate(this); 
			}
		}
	}
});
JS;

			Requirements::customScript($js);
			// HACK Notify the form that the validators client-side validation code has already been included
			if($this->form) $this->form->jsValidationIncluded = true;
		}
	}
}
