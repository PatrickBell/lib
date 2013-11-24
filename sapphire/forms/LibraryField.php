<?php
/**
 * Text input field.
 * @package forms
 * @subpackage fields-basic
 */
class LibraryField extends TextField {

	/**
	 * @var Int
	 */
	function jsValidation() {
		$formID = $this->form->FormName();
		$error = _t('LibraryField.VALIDATIONJS', 'Please enter a valid Library Card number like (U12345678).');
		$jsFunc =<<<JS
Behaviour.register({
	"#$formID": {
		validateLibraryField: function(fieldName) {
			var el = _CURRENT_FORM.elements[fieldName];
			if(!el || !el.value) return true;

		 	if(el.value.match(/^U\d{8}$/)) {
		 		return true;
		 	} else {
				validationError(el, "$error","validation");
		 		return false;
		 	} 	
		}
	}
});
JS;
		//fix for the problem with more than one form on a page.
		Requirements::customScript($jsFunc, 'func_validateLibraryField' .'_' . $formID);

		//return "\$('$formID').validateEmailField('$this->name');";
		return <<<JS
if(typeof fromAnOnBlur != 'undefined'){
	if(fromAnOnBlur.name == '$this->name')
		$('$formID').validateLibraryField('$this->name');
}else{
	$('$formID').validateLibraryField('$this->name');
}
JS;
	}
	
	/**
	 * Validates for RFC 2822 compliant email adresses.
	 * 
	 * @see http://www.regular-expressions.info/email.html
	 * @see http://www.ietf.org/rfc/rfc2822.txt
	 * 
	 * @param Validator $validator
	 * @return String
	 */
	function validate($validator){
		$this->value = trim($this->value);
		
		$pcrePattern = '^U\d{8}$';


		// PHP uses forward slash (/) to delimit start/end of pattern, so it must be escaped
		$pregSafePattern = str_replace('/', '\\/', $pcrePattern);

		if($this->value && !preg_match('/' . $pregSafePattern . '/i', $this->value)){
 			$validator->validationError(
 				$this->name,
				_t('LibraryField.VALIDATION', "Please enter a valid Library Card number like (U12345678)"),
				"validation"
			);
			return false;
		} else{
			return true;
		}
	}
}
?>