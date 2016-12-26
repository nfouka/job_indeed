<?php

namespace Drupal\jobe_find\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Entreprise entity.
 *
 * @ingroup jobe_find
 *
 * @ContentEntityType(
 *   id = "entreprise",
 *   label = @Translation("Entreprise"),
 *   handlers = {
 *     "storage" = "Drupal\jobe_find\entrepriseStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\jobe_find\entrepriseListBuilder",
 *     "views_data" = "Drupal\jobe_find\Entity\entrepriseViewsData",
 *     "translation" = "Drupal\jobe_find\entrepriseTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\jobe_find\Form\entrepriseForm",
 *       "add" = "Drupal\jobe_find\Form\entrepriseForm",
 *       "edit" = "Drupal\jobe_find\Form\entrepriseForm",
 *       "delete" = "Drupal\jobe_find\Form\entrepriseDeleteForm",
 *     },
 *     "access" = "Drupal\jobe_find\entrepriseAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\jobe_find\entrepriseHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "entreprise",
 *   data_table = "entreprise_field_data",
 *   revision_table = "entreprise_revision",
 *   revision_data_table = "entreprise_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer entreprise entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/job/find/structure/entreprise/{entreprise}",
 *     "add-form" = "/job/find/structure/entreprise/add",
 *     "edit-form" = "/job/find/structure/entreprise/{entreprise}/edit",
 *     "delete-form" = "/job/find/structure/entreprise/{entreprise}/delete",
 *     "version-history" = "/job/find/structure/entreprise/{entreprise}/revisions",
 *     "revision" = "/job/find/structure/entreprise/{entreprise}/revisions/{entreprise_revision}/view",
 *     "revision_revert" = "/job/find/structure/entreprise/{entreprise}/revisions/{entreprise_revision}/revert",
 *     "translation_revert" = "/job/find/structure/entreprise/{entreprise}/revisions/{entreprise_revision}/revert/{langcode}",
 *     "revision_delete" = "/job/find/structure/entreprise/{entreprise}/revisions/{entreprise_revision}/delete",
 *     "collection" = "/job/find/structure/entreprise",
 *   },
 *   field_ui_base_route = "entreprise.settings"
 * )
 */
class entreprise extends RevisionableContentEntityBase implements entrepriseInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the entreprise owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionUser() {
    return $this->get('revision_uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionUserId($uid) {
    $this->set('revision_uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Entreprise entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Entreprise entity.'))
      ->setRevisionable(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


      $fields['nombre_salarier_entreprise'] = BaseFieldDefinition::create('integer')
          ->setLabel(t('nombre_salarier_entreprise'))
          ->setDescription(t('nombre_salarier_entreprise'))
          ->setRevisionable(TRUE)
          ->setSettings(array(
              'max_length' => 50,
              'text_processing' => 0,
          ))
          ->setDefaultValue('')
          ->setDisplayOptions('view', array(
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'string_textfield',
              'weight' => -4,
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);


      $fields['mailer_entreprise'] = BaseFieldDefinition::create('email')
          ->setLabel(t('Mailer Entrprise'))
          ->setDescription(t('The name of the Entreprise entity.'))
          ->setRevisionable(TRUE)
          ->setSettings(array(
              'max_length' => 50,
              'text_processing' => 0,
          ))
          ->setDefaultValue('')
          ->setDisplayOptions('view', array(
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'string_textfield',
              'weight' => -4,
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);


      $fields['content_entreprise'] = BaseFieldDefinition::create('string_long')
          ->setLabel(t('Content Entrprise'))
          ->setDescription(t('The context of the Entreprise entity.'))
          ->setRevisionable(TRUE)
          ->setSettings(array(
              'max_length' => 50,
              'text_processing' => 0,
          ))
          ->setDefaultValue('')
          ->setDisplayOptions('view', array(
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'string_textfield',
              'weight' => -4,
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);



      $fields['name_entreprise'] = BaseFieldDefinition::create('string')
          ->setLabel(t('Name Entrprise'))
          ->setDescription(t('The name of the Entreprise entity.'))
          ->setRevisionable(TRUE)
          ->setSettings(array(
              'max_length' => 50,
              'text_processing' => 0,
          ))
          ->setDefaultValue('')
          ->setDisplayOptions('view', array(
              'label' => 'above',
              'type' => 'string',
              'weight' => -4,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'string_textfield',
              'weight' => -4,
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Entreprise is published.'))
      ->setRevisionable(FALSE)
      ->setDefaultValue(FALSE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_timestamp'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Revision timestamp'))
      ->setDescription(t('The time that the current revision was created.'))
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Revision user ID'))
      ->setDescription(t('The user ID of the author of the current revision.'))
      ->setSetting('target_type', 'user')
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
