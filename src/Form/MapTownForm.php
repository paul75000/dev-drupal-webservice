<?php

namespace Drupal\maptown\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
// use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class MapTownForm extends FormBase {

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
        'callback' => array($this, 'inputDepatementsAjax'),
        'event' => 'change', 
      ],
      '#suffix' => '<span class="text-message"></span>',
    ];
    
    $form['departements'] = [
    	'#id' => 'departements',
      '#type' => 'select',
      '#title' => t('departements'),
      '#required' => TRUE,
      '#description' => t('Select a departements'),
      '#options' => [
        
      ],
      '#ajax' => [
        'callback' => array($this, 'inputVillesAjax'),
        'event' => 'change', 
      ],
      '#suffix' => '<span class="text-message-communes"></span>',
      '#access' => FALSE,
     ];

     $form['communes'] = [
      '#type' => 'select',
      '#title' => t('communes'),
      '#required' => TRUE,
      '#description' => t('Select a communes'),
      '#options' => [],
      '#access' => FALSE,
     ];


    // $form['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('expose data'),

    // ];
   //ksm($form);
  	return $form;
	}
  
   public function inputDepatementsAjax(array &$form, FormStateInterface $form_state){
      
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

      $form['departements']['#access'] = TRUE;
      $response->addCommand(new HtmlCommand('.text-message', $form['departements']));
      return $response;
  }

  public function inputVillesAjax(array &$form, FormStateInterface $form_state){
      
      $message = $form_state->getValue('departements');
      $response = new AjaxResponse();

      $client = \Drupal::service('http_client');

      $request = $client->get('https://geo.api.gouv.fr/departements/' . $message .'/communes',  ['Accept' => 'application/json']);
      $out = $request->getBody();
      $content = $out->getContents();
      $content = json_decode($content);
      foreach ($content as $data) {
  	    $form['communes']['#options'][$data->code] = $data->nom;
      }

      $form['communes']['#access'] = TRUE;
      $response->addCommand(new HtmlCommand('.text-message-communes', $form['communes']));
      return $response;
  }


  public function submitForm(array &$form, FormStateInterface $form_state){

  }

}