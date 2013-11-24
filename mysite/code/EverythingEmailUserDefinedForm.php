<?php
/**
 * A user defined form that sends out the contents of literal fields in email in addition to all the regular content
 */
class EverythingEmailUserDefinedForm extends UserDefinedForm {

}

class EverythingEmailUserDefinedForm_Controller extends UserDefinedForm_Controller {

	/**
	 * Process the form that is submitted through the site
	 *
	 * @param Array Data
	 * @param Form Form
	 * @return Redirection
	 */
	function process($data, $form) {
		$submittedForm = Object::create('SubmittedForm');
		$submittedForm->SubmittedByID = ($id = Member::currentUserID()) ? $id : 0;
		$submittedForm->ParentID = $this->ID;

		// if saving is not disabled save now to generate the ID
		if(!$this->DisableSaveSubmissions) $submittedForm->write();

		$values = array();
        $attachments = array();

		$submittedFields = new DataObjectSet();

		foreach($this->Fields() as $field) {

			// create a new submitted form field.
			$submittedField = $field->getSubmittedFormField();
			$submittedField->ParentID = $submittedForm->ID;
			$submittedField->Name = $field->Name;
			$submittedField->Title = $field->Title;

			// save the value from the data
			if($field->hasMethod('getValueFromData')) {
				$submittedField->Value = $field->getValueFromData($data);
			}
			else {
				if(isset($data[$field->Name])) $submittedField->Value = $data[$field->Name];

				if($field->ClassName == "EditableLiteralField") {
					$submittedField->Title = "";    //no title needed, it is included in the content
					$submittedField->Name = $field->Title;
					$submittedField->Value .= $field->getFormField()->Content;
				}
			}

			if(!empty($data[$field->Name])){
				if(in_array("EditableFileField", $field->getClassAncestry())) {
					if(isset($_FILES[$field->Name])) {

						// create the file from post data
						$upload = new Upload();
						$file = new File();

						$upload->loadIntoFile($_FILES[$field->Name], $file);

						// write file to form field
						$submittedField->UploadedFileID = $file->ID;

						// attach a file only if lower than 1MB
						if($file->getAbsoluteSize() < 1024*1024*1){
							$attachments[] = $file;
						}
					}
				}
			}

			if(!$this->DisableSaveSubmissions) $submittedField->write();

			$submittedFields->push($submittedField);
		}

		$emailData = array(
			"Sender" => Member::currentUser(),
			"Fields" => $submittedFields
		);

		// email users on submit.
		if($this->EmailRecipients()) {

			$email = new UserDefinedForm_SubmittedFormEmail($submittedFields);
			$email->populateTemplate($emailData);

			if($attachments){
				foreach($attachments as $file){
					if($file->ID != 0) {
						$email->attachFile($file->Filename,$file->Filename, $file->getFileType());
					}
				}
			}

			foreach($this->EmailRecipients() as $recipient) {
				$email->populateTemplate($recipient);
				$email->populateTemplate($emailData);
				$email->setFrom($recipient->EmailFrom);
				$email->setBody($recipient->EmailBody);
				$email->setSubject($recipient->EmailSubject);
				$email->setTo($recipient->EmailAddress);

				// check to see if they are a dynamic sender. eg based on a email field a user selected
				if($recipient->SendEmailFromField()) {
					$submittedFormField = $submittedFields->find('Name', $recipient->SendEmailFromField()->Name);
					if($submittedFormField) {
						$email->setFrom($submittedFormField->Value);
					}
				}
				// check to see if they are a dynamic reciever eg based on a dropdown field a user selected
				if($recipient->SendEmailToField()) {
					$submittedFormField = $submittedFields->find('Name', $recipient->SendEmailToField()->Name);

					if($submittedFormField) {
						$email->setTo($submittedFormField->Value);
					}
				}

				if($recipient->SendPlain) {
					$body = strip_tags($recipient->EmailBody) . "\n ";
					if(isset($emailData['Fields']) && !$recipient->HideFormData) {
						foreach($emailData['Fields'] as $Field) {
							$body .= $Field->Title .' - '. $Field->Value .' \n';
						}
					}
					$email->setBody($body);
					$email->sendPlain();
				}
				else {
					$email->send();
				}
			}
		}

		$referrer = (isset($data['Referrer'])) ? '?referrer=' . urlencode($data['Referrer']) : "";

		return Director::redirect($this->Link() . 'finished' . $referrer);
	}
}