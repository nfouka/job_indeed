<?php

namespace Drupal\jobe_find;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface entrepriseStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Entreprise revision IDs for a specific Entreprise.
   *
   * @param \Drupal\jobe_find\Entity\entrepriseInterface $entity
   *   The Entreprise entity.
   *
   * @return int[]
   *   Entreprise revision IDs (in ascending order).
   */
  public function revisionIds(entrepriseInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Entreprise author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Entreprise revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\jobe_find\Entity\entrepriseInterface $entity
   *   The Entreprise entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(entrepriseInterface $entity);

  /**
   * Unsets the language for all Entreprise with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
