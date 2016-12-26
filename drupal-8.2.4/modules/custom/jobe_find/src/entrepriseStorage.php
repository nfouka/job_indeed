<?php

namespace Drupal\jobe_find;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\jobe_find\Entity\entrepriseInterface;

/**
 * Defines the storage handler class for Entreprise entities.
 *
 * This extends the base storage class, adding required special handling for
 * Entreprise entities.
 *
 * @ingroup jobe_find
 */
class entrepriseStorage extends SqlContentEntityStorage implements entrepriseStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(entrepriseInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {entreprise_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {entreprise_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(entrepriseInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {entreprise_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('entreprise_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
