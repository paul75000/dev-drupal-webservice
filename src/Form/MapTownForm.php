<?php

namespace Drupal\maptown\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class MapTownForm extends FormBase{
	public function getFormID(){
		return 'map-form';
	}

	public function buildForm(array $form, FormStateInterface $form_state){
    $form['regions'] = [
      '#type' => 'select',
      '#title' => t('Regions'),
      '#required' => TRUE,
      '#description' => t('Select a region'),
      '#options' => [
        
      ],
      '#ajax' => [
        'callback' => array($this, 'inputDepatementAjax'),
        'event' => 'change', 
      ],
      '#suffix' => '<span class="text-message"></span>',
    ];
    
    $form['departements'] = [
      '#type' => 'select',
      '#title' => t('departements'),
      '#required' => TRUE,
      '#description' => t('Select a departements'),
      '#options' => [
        
      ],
      '#access' => FALSE,
     ];
    

    // $form['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('expose data'),

    // ];
   // ksm($form);
  	return $form;
	}
  
    public function inputDepatementAjax(array &$form, FormStateInterface $form_state){
      
      $message =  $form_state->getValue('regions');
      $response = new AjaxResponse();

      $client = \Drupal::service('http_client');

      $request = $client->get('https://geo.api.gouv.fr/regions/' . $message .'/departements',  ['Accept' => 'application/json']);
      $out = $request->getBody();
      $content = $out->getContents();
      $content = json_decode($content);
      foreach ($content as $data) {
  	   $form['departements']['#options'][$data->code] = $data->nom;
       }

      $form['departement']['#access'] = TRUE;

      $response->addCommand(new HtmlCommand('.text-message', $form);
      return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){

  }

}