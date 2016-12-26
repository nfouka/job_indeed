<?php

namespace Drupal\jobe_find\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Entreprise entities.
 *
 * @ingroup jobe_find
 */
interface entrepriseInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Entreprise name.
   *
   * @return string
   *   Name of the Entreprise.
   */
  public function getName();

  /**
   * Sets the Entreprise name.
   *
   * @param string $name
   *   The Entreprise name.
   *
   * @return \Drupal\jobe_find\Entity\entrepriseInterface
   *   The called Entreprise entity.
   */
  public function setName($name);

  /**
   * Gets the Entreprise creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Entreprise.
   */
  public function getCreatedTime();

  /**
   * Sets the Entreprise creation timestamp.
   *
   * @param int $timestamp
   *   The Entreprise creation timestamp.
   *
   * @return \Drupal\jobe_find\Entity\entrepriseInterface
   *   The called Entreprise entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Entreprise published status indicator.
   *
   * Unpublished Entreprise are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Entreprise is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Entreprise.
   *
   * @param bool $published
   *   TRUE to set this Entreprise to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\jobe_find\Entity\entrepriseInterface
   *   The called Entreprise entity.
   */
  public function setPublished($published);

  /**
   * Gets the Entreprise revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Entreprise revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\jobe_find\Entity\entrepriseInterface
   *   The called Entreprise entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Entreprise revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Entreprise revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\jobe_find\Entity\entrepriseInterface
   *   The called Entreprise entity.
   */
  public function setRevisionUserId($uid);

}
