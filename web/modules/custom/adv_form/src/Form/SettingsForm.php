<?php

namespace Drupal\adv_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Advanced Form Task Module settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor for the Controller.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Dependency injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'adv_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['adv_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $term = $this->config('adv_form.settings')->get('tags');

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $this->config('adv_form.settings')->get('title'),
    ];
    $form['advanced'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Advanced'),
      '#default_value' => $this->config('adv_form.settings')->get('advanced'),
    ];
    $form['tags'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Tags'),
      '#target_type' => 'taxonomy_term',
      '#default_value' => $this->entityTypeManager->getStorage('taxonomy_term')->load($term),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('adv_form.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('advanced', $form_state->getValue('advanced'))
      ->set('tags', $form_state->getValue('tags'))

      ->save();
    parent::submitForm($form, $form_state);
  }

}
