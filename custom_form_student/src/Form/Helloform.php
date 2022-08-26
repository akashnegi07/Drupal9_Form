<?php
namespace Drupal\custom_form_student\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Link;


class Helloform extends FormBase {
    public function getFormId(){
        return 'custom_form_student_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state){
        $form['fname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#required' => TRUE,
            '#maxlength' => 30,
            '#default_value' => '',
        ];
        $form['sname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Number'),
            '#required' => TRUE,
            '#maxlength' => 30,
            '#default_value' => '',
        ];

        $form['age'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Age'),
            '#required' => TRUE,
            '#maxlength' => 20,
            '#default_value' => '',
        ];

        $form['address'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Address'),
            '#required' => TRUE,
            '#maxlength' => 120,
            '#default_value' => '',
          ];
          $form['email'] = [
            '#type' => 'email',
            '#title' => t('Email'),
            '#required' => TRUE,
            '#default_value' => "",
            '#description' => "Please enter your email.",
            '#size' => 20,
            '#maxlength' => 20,
          ];
          $form['profile_image'] = [
            '#type' => 'managed_file',
            '#title' => t('Profile Picture'),
            '#upload_validators' => array(
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000),
            ),
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
            '#upload_location' => 'public://profile-pictures',
            '#required' => TRUE,
         ];

        $state_option = static::gerFirstDropdownOptions();
        if(empty($form_state->getValue('state_dropdown'))){
            
            $selected_options = key($state_option);
            
        }
        else{
            $selected_options = $form_state->getValue('state_dropdown');
        }
        $form['option_state_fieldset'] = [
            '#type' => 'fieldset',
            '#title' => t('Choose State'),
        ];
        $form['option_state_fieldset']['state_dropdown'] = [
            '#type' => 'select',
            '#title' => t('State'),
            '#options' => $state_option,
            '#default_value' => $selected_options,
            '#ajax' => [
                'callback' => '::instrumentDropdownCallback',
                'wrapper'  => 'state-fieldset-container',
                'event' => 'change',
            ],
        ];

        $form['select_fieldset_container'] = [
            '#type' => 'fieldset',
            '#title' => t('Choose City'),
            '#attributes' => ['id' => 'state-fieldset-container'],
        ];
        $form['select_fieldset_container']['select_fieldset']['select_dropdown'] = [
            '#type' => 'select',
            '#title' => $state_option[$selected_options] . ' ' . $this->t('State'),
            '#options' => static::getSecondDropdownOptions($selected_options),
            '#default_value' => !empty($form_state->getValue('select_dropdown')) ? $form_state->getValue('select_dropdown') : '',
            '#ajax' => [
                'callback' => '::instrumentDropdownCallback1',
                'wrapper'  => 'local-fieldset-container',
                'event' => 'change',
            ],
        ];

        $form['select_fieldset_container1'] = [
            '#type' => 'container',
            '#attributes' => ['id' => 'local-fieldset-container'],
        ];
        $form['select_fieldset_container1']['select_fieldset1']['select_dropdown1'] = [
            '#type' => 'select',
            '#title' => $form_state->getValue('select_dropdown') . ' ' . $this->t('City'),
            '#options' => static::getThirdDropdownOptions($form_state->getValue('select_dropdown')),
            '#default_value' => !empty($form_state->getValue('select_dropdown1')) ? $form_state->getValue('select_dropdown1') : '',
        ];

        $form['select_fieldset_container1']['select_fieldset1']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];
        if ($selected_option == 'none') {

            $form['select_fieldset_container']['select_fieldset']['select_dropdown']['#title'] = $this->t('You must choose an state first.');
            $form['select_fieldset_container']['select_fieldset']['select_dropdown']['#disabled'] = TRUE;
            $form['select_fieldset_container']['select_fieldset']['submit']['#disabled'] = TRUE;
          }

          return $form;
           

    }
    public function instrumentDropdownCallback(array $form, FormStateInterface $form_state) {
        return $form['select_fieldset_container'];
      }

      public function instrumentDropdownCallback1(array $form, FormStateInterface $form_state) {
        return $form['select_fieldset_container1'];
      }
    public static function gerFirstDropdownOptions(){
        
        return[
            'none' => 'none',
            'Gujarat' => 'Gujarat',
            'Madhya Pradesh' => 'Madhya Pradesh',
            'Uttarkhand' => 'Uttarkhand',
            'Uttar Pradesh' => 'Uttar Pradesh',
        ];
    }

    public static function getSecondDropdownOptions($key = ''){
        switch ($key) {
            case 'Gujarat':
                $options = [
                    'none' => 'none',
                    'Gandhinagar' => 'Gandhinagar',
                    'Ahmedabad' => 'Ahmedabad',
                    'Surat' => 'Surat',
                    'Vadodara' => 'Vadodara',
                ];
                break;

            case 'Madhya Pradesh':
                $options = [
                    'none' => 'none',
                    'Indore' => 'Indore',
                    'Bhopal' => 'Bhopal',
                    'Jabalpur' => 'Jabalpur',
                    'Ahirkhedi' => 'Ahirkhedi',
                ];
                break;
            case 'Uttarkhand':
                $options = [
                    'none' => 'none',
                    'Dehradun' => 'Dehradun',
                    'Haridwar' => 'Haridwar',
                    'Rishikesh' => 'Rishikesh',
                    'Nainital' => 'Nainital',
                    ];
                break;
            case 'Uttar Pradesh':
                $options = [
                    'none' => 'none',
                    'Kanpur' => 'Kanpur',
                    'Lucknow' => 'Lucknow',
                    'Ayodhya' => 'Ayodhya',
                    'Gorakhpur' => 'Gorakhpur',
                    ];
                break;
            default:
               $options = ['none' => 'none'];
               break;
        }
        return $options;
    }

    public static function getThirdDropdownOptions($key = ''){
        switch ($key) {
            case 'Gandhinagar':
                $options = [
                    'Gandhinagar1' => 'Gandhinagar1',
                    'Gandhinagar2' => 'Gandhinagar2',
                    'Gandhinagar3' => 'Gandhinagar3',
                    'Gandhinagar4' => 'Gandhinagar4',
                ];
                break;


            case 'Indore':
                $options = [
                    'Indore1' => 'Indore1',
                    'Indore2' => 'Indore2',
                    'Indore3' => 'Indore3',
                    'Indore4' => 'Indore4',
                ];
                break;
            case 'Dehradun':
                $options = [
                    'Dehradun1' => 'Dehradun1',
                    'Dehradun2' => 'Dehradun2',
                    'Dehradun3' => 'Dehradun4',
                    'Dehradun4' => 'Dehradun4',
                    ];
                break;
            case 'Kanpur':
                $options = [
                    'Kanpur1' => 'Kanpur1',
                    'Kanpur2' => 'Kanpur2',
                    'Kanpur3' => 'Kanpur3',
                    'Kanpur4' => 'Kanpur4',
                    ];
                break;
            default:
               $options = ['none' => 'none'];
               break;
        }
        return $options;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $field = $form_state->getValues();
        if(!$form_state->getValue('fname') || empty($form_state->getValue('fname')))
        {
            $form_state->setErrorByName('fname', $this->t('Provide First Name'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
        if($conn = Database::getConnection()){
        $fields["fname"] = $form_state->getValue('fname');
		$fields["sname"] = $form_state->getValue('sname');
		$fields["age"] = $form_state->getValue('age');
		$fields["address"] = $form_state->getValue('address');
        $fields["email"] = $form_state->getValue('email');
        $fields["City"] = $form_state->getValue('state_dropdown');
        $fields["State"] = $form_state->getValue('select_dropdown');
        $fields["Location"] = $form_state->getValue('select_dropdown1');
        
        
        $conn->insert('students')->fields($fields)->execute();
        \Drupal::messenger()->addMessage('done');
        }

        
        

       // \Drupal::messenger()->addMessage($form_state->getValue('fname'));
        //\Drupal::messenger()->addMessage($form_state->getValue('sname'));
    }
}