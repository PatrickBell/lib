<?php

class ReferrerForm extends UserDefinedForm {
	
}

class ReferrerForm_Controller extends UserDefinedForm_Controller {

	function Form() {
		$form = parent::Form();
		if (!$form) return $form;
		
		// Inject the hidden information to be stored on submission
		$referrer = (empty($_SERVER['HTTP_REFERER'])) ? getenv('HTTP_REFERER') : $_SERVER['HTTP_REFERER'];
		$fields = $form->Fields();
		$ballot = new HiddenField('Referrer', 'Referrer', $referrer);
		$fields->push($ballot);
		
		return $form;
	}

	/**
	* Copypasted from UserDefinedForm to inject information.
	*/
	function process($data, $form) {
		// submitted form object
		$submittedForm = new SubmittedForm();
		$submittedForm->SubmittedByID = ($id = Member::currentUserID()) ? $id : 0;
		$submittedForm->ParentID = $this->ID;
		$submittedForm->Recipient = $this->EmailTo;
		if(!$this->DisableSaveSubmissions) $submittedForm->write();
		
		// email values
		$values = array();
		$recipientAddresses = array();
		$sendCopy = false;
		$attachments = array();

		$submittedFields = new DataObjectSet();
		
		foreach($this->Fields() as $field) {
			// don't show fields that shouldn't be shown
			if(!$field->showInReports()) continue;
			
			$submittedField = $field->getSubmittedFormField();
			$submittedField->ParentID = $submittedForm->ID;
			$submittedField->Name = $field->Name;
			$submittedField->Title = $field->Title;
			
			if($field->hasMethod('getValueFromData')) {
				$submittedField->Value = $field->getValueFromData($data);
			}
			else {
				if(isset($data[$field->Name])) $submittedField->Value = $data[$field->Name];
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
						
						// Attach the file if its less than 1MB, provide a link if its over.
						if($file->getAbsoluteSize() < 1024*1024*1){
							$attachments[] = $file;
						}
					}									
				}
			}
			if(!$this->DisableSaveSubmissions) $submittedField->write();
			
			$submittedFields->push($submittedField);
		}
		
		// Add the referrer information from the hidden field directly into the DB
		if (isset($data['Referrer'])) {
			$field = new SubmittedFormField;
			$field->ParentID = $submittedForm->ID;
			$field->Name = "Referrer";
			$field->Title = "Referrer";
			$field->Value = $data['Referrer'];
			$field->write();
		
			$submittedField = new SubmittedFormField;
			$submittedField->ParentID = $submittedForm->ID;
			$submittedField->Name = "Referrer";
			$submittedField->Title = "Referrer";
			$submittedField->Value = '<a href="'.$data['Referrer'].'">'.$data['Referrer'].'</a>';
			$submittedFields->push($submittedField);
		}
		
		$emailData = array(
			"Sender" => Member::currentUser(),
			"Fields" => $submittedFields
			);

		// email users on submit. All have their own custom options. 
		if($this->EmailRecipients()) {
			$email = new UserDefinedForm_SubmittedFormEmail($submittedFields);                     
			$email->populateTemplate($emailData);
			if($attachments){
				foreach($attachments as $file){
					// bug with double decorated fields, valid ones should have an ID.
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
		
		return Director::redirect($this->Link() . 'finished?referrer=' . urlencode($data['Referrer']));
	}

	
}