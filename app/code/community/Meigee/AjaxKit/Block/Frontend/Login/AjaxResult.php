
<?php
class Meigee_AjaxKit_Block_Frontend_Login_AjaxResult extends Meigee_AjaxKit_Block_Frontend_AjaxResult
{
    private $ajax_values = array();
    protected $result = array();

    function ajax($action, $values)
    {
        $this->ajax_values = $values;
        if (isset($values['form_values']))
        {
            $this->getRequest()->setParams($values['form_values']);
        }

        switch ($action) {
            case 'get_login_popup':
                $this->getLoginPopup();
                break;
            case 'processing_user_form':
                switch ($this->ajax_values['form_id'])
                {
                    case 'form_login_html':
                        $this->login();
                        $this->redirectAction('login');
                        break;
                    case 'form_register_html':
                        $this->register();
                        $this->redirectAction('registration');
                        break;
                    case 'form_forgot_password_html':
                        $this->forgotPassword();
                        $this->redirectAction('forgot_password');
                        break;
                }
                $this->getLoginHeader();
                $this->getSuccessBottom();
                break;
            case 'logout':
                $this->logout();
                $this->redirectAction('logout');
                $this->getLoginHeader();
                $this->getSuccessBottom();
                break;
        }

//        if ((isset($this->result['popup_content']))
//            && (isset($this->result['redirect_to']))
//            && (isset($this->result['popup_content']['text']))
//            && (!$this->result['redirect_to'])
//            && (!empty($this->result['popup_content']['text']))
//        )
//        {
//            $session = Mage::getSingleton('customer/session');
//            $session->addSuccess($this->result['popup_content']['text']);
//        }

        if (isset($this->result['popup_content'])) {
            $this->result['popup_html'] = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_addToLinks_Popup')
                ->setPopupContent($this->result['popup_content'])
                ->toHtml();
            unset($this->result['popup_content']);
        }
        return $this->result;
    }

    function getLoginPopup()
    {
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array('head'=>'page/html_head'), array(
            'ajaxkit_login_popup'
        ));
        $this->result['form_login_html'] = $layout->getBlock("customer_form_login")->toHtml();
        $this->result['form_register_html'] = $layout->getBlock("customer_form_register")->toHtml();
        $this->result['form_forgot_password_html'] = $layout->getBlock("forgotPassword")->toHtml();
        $this->result['login_buttons'] = $layout->getBlock("login_buttons")->toHtml();
        $this->result['popup_content']['bool'] = true;
        Mage::getModel('ajaxKit/updateLayout')->getLayoutJsCss($this->result);
    }

    function logout()
    {
        $session = Mage::getSingleton('customer/session');
        $session->logout()->renewSession();
        $this->result['popup_content']['text'] =$this->__('Logout success.');
        $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
        $this->result['reload'] = true;
    }

    public function getLoginHeader()
    {
        $session = Mage::getSingleton('customer/session');
        if ($session->isLoggedIn())
        {
            $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('default', 'customer_logged_in'));
            $url_key = '/customer/account/logout/';
        }
        else
        {
            $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('default', 'customer_logged_out'));
            $url_key = '/customer/account/login/';
        }
    }

    public function forgotPassword()
    {
        $ajax_values = $this->helper('ajaxKit')->parseParamsByAttributes($this->ajax_values['form_values']);
        $email = (string)$ajax_values['email'];
        if ($email)
        {
            if ($this->checkCaptcha('user_forgotpassword', $ajax_values))
            {
                $customer_session = Mage::getSingleton('customer/session');
                if (!Zend_Validate::is($email, 'EmailAddress'))
                {
                    $customer_session->setForgottenEmail($email);
                    $this->result['message_error'][] = $this->__('Invalid email address.');
                    return;
                }

                /** @var $customer Mage_Customer_Model_Customer */
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email);

                if ($customer->getId())
                {
                    try
                    {
                        $newResetPasswordLinkToken =  $this->helper('customer')->generateResetPasswordLinkToken();
                        $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                        $customer->sendPasswordResetConfirmationEmail();
                    }
                    catch (Exception $exception)
                    {
                        $this->result['message_error'][] =$exception->getMessage();
                        return;
                    }
                }
                $this->result['popup_content']['text'] = $this->helper('customer')
                    ->__('If there is an account associated with %s you will receive an email with a link to reset your password.',
                        $this->helper('customer')->escapeHtml($email));
                $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                return;
            }
        }
        else
        {
            $this->result['message_error'][] = $this->__('Please enter your email.');
            return;
        }
    }



    public function login()
    {
        $customer_session = Mage::getSingleton('customer/session');
        if ($customer_session->isLoggedIn())
        {
            $this->result['message_error'][] = $this->__('You are already logged in.');
            return;
        }

        if ($this->getRequest()->isPost())
        {
            $login = $this->helper('ajaxKit')->parseParamsByAttributes($this->ajax_values['form_values']);
            $login = $login['login'];

            if (!empty($login['username']) && !empty($login['password']))
            {
                if ($this->checkCaptcha('user_login', $login))
                {
                    try
                    {
                        $customer_session->login($login['username'], $login['password']);
                        if ($customer_session->isLoggedIn())
                        {
                            $this->result['popup_content']['text'] = $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName());
                            $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                            $this->result['reload'] = true;
                        }
                    }
                    catch (Mage_Core_Exception $e)
                    {
                        switch ($e->getCode())
                        {
                            case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                                $value = $this->helper('customer')->getEmailConfirmationUrl($login['username']);
                                $message = $this->helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                                break;
                            case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                                $message = $e->getMessage();
                                break;
                            default:
                                $message = $e->getMessage();
                        }

                        $this->result['message_error'][] = $message;
                        $customer_session->setUsername($login['username']);
                    }
                    catch (Exception $e)
                    {
                        // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                    }
                }
            }
            else
            {
                $this->result['message_error'][] = $this->__('Login and password are required.');
            }
        }
    }

    function register()
    {
        $session = Mage::getSingleton('customer/session');
        if ($session->isLoggedIn())
        {
            return;
        }
        $register_form = $this->helper('ajaxKit')->parseParamsByAttributes($this->ajax_values['form_values']);
        $customer = $this->_getCustomer($register_form['is_subscribed']);

        try
        {
            if ($this->checkCaptcha('user_create', $register_form))
            {
                $errors = $this->_getCustomerErrors($customer);
                if (empty($errors))
                {
                    if (method_exists($customer, 'cleanPasswordsValidationData'))
                    {
                        $customer->cleanPasswordsValidationData();
                    }
                    $customer->save();
                    Mage::dispatchEvent('customer_register_success',
                        array('account_controller' => $this, 'customer' => $customer)
                    );
                    if ($customer->isConfirmationRequired())
                    {
                        $store = Mage::app()->getStore();
                        $customer->sendNewAccountEmail(
                            'confirmation',
                            $session->getBeforeAuthUrl(),
                            $store->getId()
                        );
                        $this->result['popup_content']['text'] =$this->__('Account confirmation is required. Please, check your email for the confirmation link.');
                        $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                    }
                    else
                    {
                        $session->setCustomerAsLoggedIn($customer);
						$customer->sendNewAccountEmail(
							'registered',
							Mage::app()->getStore()->getId(),
							$this->getRequest()->getPost('password')
						);
                        $this->result['popup_content']['text'] =$this->__('Registration success.');
                        $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                    }
                    return;
                }
                else
                {
                    $this->result['message_error'] = $errors;
                }
            }

        }
        catch (Mage_Core_Exception $e)
        {
            $session->setCustomerFormData($register_form);
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS)
            {
                $message = $this->__('There is already an account with this email address.');
                $session->setEscapeMessages(false);
            }
            else
            {
                $message = $e->getMessage();
            }
            $this->result['message_error'][] = $message;
        }
        catch (Exception $e)
        {
            $session->setCustomerFormData($register_form)
                ->addException($e, $this->__('Cannot save the customer.'));
        }
    }


    protected function _getCustomer($is_subscribed)
    {
        $customer = Mage::registry('current_customer');
        if (!$customer)
        {
            $customer = Mage::getModel('customer/customer')->setId(null);
        }
        if ($is_subscribed)
        {
            $customer->setIsSubscribed(1);
        }
        $customer->getGroupId();
        return $customer;
    }

    protected function _getCustomerErrors($customer)
    {
        $request = $this->getRequest();
        $errors = array();
        if ($request->getPost('create_address'))
        {
            $errors = $this->_getErrorsOnCustomerAddress($customer);
        }

        $customerForm = $this->_getCustomerForm($customer);
        $customerData = $customerForm->extractData($request);
        $customerErrors = $customerForm->validateData($customerData);
        if ($customerErrors !== true)
        {
            $errors = array_merge($customerErrors, $errors);
        }
        else
        {
            $customerForm->compactData($customerData);
            $customer->setPassword($request->getParam('password'));
            $customer->setPasswordConfirmation($request->getParam('confirmation'));
            $customer->setConfirmation($request->getParam('confirmation'));

            $customerErrors = $customer->validate();
            if (is_array($customerErrors)) {
                $errors = array_merge($customerErrors, $errors);
            }
        }
        return $errors;
    }
    protected function _getCustomerForm($customer)
    {
        $customerForm = Mage::getModel('customer/form');
        $customerForm->setFormCode('customer_account_create');
        $customerForm->setEntity($customer);
        return $customerForm;
    }



    protected function redirectAction($type)
    {
        $is_go_to_home = false;
        if (isset($this->ajax_values['pathname']))
        {
            $pattern = array('/\/customer\//', '/\/sales\//', '/\/wishlist\//', '/\/oauth\//', '/\/downloadable\//', '/\/newsletter\//', '/\/checkout\//');
            $str_len = strlen($this->ajax_values['pathname']);
            $new_str = preg_replace($pattern, '', $this->ajax_values['pathname']);
            $is_go_to_home = $str_len != strlen($new_str);
        }

        switch ($type)
        {
            case 'login':
                $main = 'after_login';
                $custom_url = 'after_login_custom_url';
                $magento_pages = 'after_login_magento_pages';
                $static_pages = 'after_login_static_pages';
                break;
            case 'logout':
                $main = 'after_logout';
                $custom_url = 'after_logout_custom_url';
                $magento_pages = 'after_logout_magento_pages';
                $static_pages = 'after_logout_static_pages';
                break;
            case 'registration':
                $main = 'after_registration';
                $custom_url = 'after_registration_custom_url';
                $magento_pages = 'after_registration_magento_pages';
                $static_pages = 'after_registration_static_pages';
                break;
            case 'forgot_password':
                $main = 'after_forgot_password';
                $custom_url = 'after_forgot_password_custom_url';
                $magento_pages = 'after_forgot_password_magento_pages';
                $static_pages = 'after_forgot_password_static_pages';
                break;
        }

        $main_config = Mage::getModel('ajaxKit/configsReader')->getConfig('general_login', $main);

        $magento_pages_config = false;
        if ($is_go_to_home && 'no_redirection' == $main_config)
        {
            $main_config =  'magento_pages';
            $magento_pages_config = 'home_page';
        }

        $url = false;
        switch ($main_config)
        {
            case 'reload':
                $this->result['reload'] = true;
            case 'custom_url':
                $custom_url_config = trim(Mage::getModel('ajaxKit/configsReader')->getConfig('general_login', $custom_url));
                $url = empty($custom_url_config) ? false : $custom_url_config;
                break;
            case 'magento_pages':
                $magento_pages_config = $magento_pages_config ? $magento_pages_config : Mage::getModel('ajaxKit/configsReader')->getConfig('general_login', $magento_pages);
                switch ($magento_pages_config)
                {
                    case 'my_account':
                        $url = Mage::getUrl('customer/account', array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure()));
                        break;
                    case 'home_page':
                        $url = Mage::getBaseUrl();
                        break;
                    case 'my_cart':
                        $url = Mage::helper('checkout/cart')->getCartUrl();
                        break;
                }
                break;
            case 'static_pages':
                $static_pages_config = Mage::getModel('ajaxKit/configsReader')->getConfig('general_login', $static_pages);
                $url = Mage::helper('cms/page')->getPageUrl($static_pages_config);
                break;
        }
        $this->result['redirect_to'] =$url;
    }


    private function getSuccessBottom()
    {
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array('head'=>'page/html_head'), array(
            'ajaxkit_login_popup'
        ));

        $success_bottom = $layout->getBlock("ajaxkit_login_popup_success_bottom");
        if ($success_bottom)
        {
            $this->result['popup_bottom'] = $success_bottom->toHtml();
        }
    }

    private function checkCaptcha($formId, $request)
    {
        if (!class_exists('Mage_Captcha_Helper_Data'))
        {
            return true;
        }


        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired())
        {
            $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
            if ($captchaModel->isRequired())
            {
                if (!isset($request['captcha']) || !isset($request['captcha'][$formId]) || !$captchaModel->isCorrect($request['captcha'][$formId]))
                {
                    $this->result['message_error'][] = Mage::helper('captcha')->__('Incorrect CAPTCHA.');
                    return false;
                }
            }
        }
        return true;
    }
}
