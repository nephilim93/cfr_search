<?php

namespace Drupal\cfr_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CfrSeachForm.
 *
 * @package Drupal\cfr_search\Form
 */
class CfrSearchForm extends FormBase {

  /**
   * Implements the buildForm function.
   *
   * @return Object
   *   The form object.
   *
   * @todo
   * .   Set default value when a parameter is in the URL.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Declare an empty form array.
    $form = [];
    // Add a textfield named search_term.
    $form['search_term'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => 'Search for Wikipedia Articles in English:',
    ];
    // Added by convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];
    // Add a submit button.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];
    return $form;
  }

  /**
   * Implements the getFormId function.
   *
   * @return string
   *   The unique form id.
   */
  public function getFormId() {
    return 'cfr_search_form';
  }

  /**
   * Implements the submitForm function. This is the form handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the term value from the textfield.
    $search_term = urlencode($form_state->getValue('search_term'));
    // Append the current term to the router as a parameter.
    $form_state->setRedirect('cfr_search_page', ['search_term' => $search_term]);
  }

}
