<?php
class Fp_Prescription_IndexController extends Mage_Core_Controller_Front_Action
{
   
	public function indexAction()
    {
        $this->loadLayout();     
            $this->renderLayout();
    }
    public function sendemailAction()
    {
        $post = $this->getRequest()->getPost();
		//Fetch submited params
        $params = $this->getRequest()->getParams();
        $mail = new Zend_Mail();
		
		
				
				 

				
				
				
								
				$emailmessage=$emailmessage."\n\n\n Prescription Uploaded ";
				$emailmessage= $emailmessage."\n\nYour Name: ".$params['name'];
				$emailmessage= $emailmessage."\n\nEmail: ".$params['email'];
				$emailmessage= $emailmessage."\n\nMesssage: ".$params['comment'];
				
				
				
				
				
				
				
				
				// Attachment
				
				/**************************************************************/
                $fileName = '';
				$attachedfile='';
                if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
                    try {
                        $fileName       = $_FILES['attachment']['name'];
                        $fileExt        = strtolower(substr(strrchr($fileName, ".") ,1));
                        $fileNamewoe    = rtrim($fileName, $fileExt);
                        $fileName       = preg_replace('/\s+', '', $fileNamewoe) . time() . '.' . $fileExt;
 
                        $uploader       = new Varien_File_Uploader('attachment');
                        $uploader->setAllowedExtensions(array('doc', 'docx','pdf', 'jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS . 'prescription';
                        if(!is_dir($path)){
                            mkdir($path, 0777, true);
                        }
						
                        $uploader->save($path . DS, $fileName );
						
						$attachedfile="http://shopfocuspoint.com/media/prescription/". $fileName;
 
                    } catch (Exception $e) {
                        $error = true;
                    }
                }
                /**************************************************************/
				
				
				
				
				
				$emailmessage=$emailmessage."\n\nPrescription: ".$attachedfile;
				
				
				
				
				$toemail='adukiakopila@gmail.com';
				//$toemail='info@mysmink.com';
				//$toemail='';
				
        $mail->setBodyText($emailmessage);
        $mail->setFrom($params['email'], $params['yname']);
        $mail->addTo($toemail, 'Admin');
        $mail->setSubject('Prescription Uploaded');
        try {
            $mail->send();
			Mage::getSingleton('core/session')->addSuccess('Thank you for your submission.');
        }
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email. ');
        }
        //Redirect back to index action of (this) inchoo-simplecontact controller
        $this->_redirect('prescription#message/');
    }
}





?>