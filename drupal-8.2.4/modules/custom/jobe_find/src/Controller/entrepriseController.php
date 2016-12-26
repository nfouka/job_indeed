<?php

namespace Drupal\jobe_find\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\jobe_find\Entity\entrepriseInterface;

/**
 * Class entrepriseController.
 *
 *  Returns responses for Entreprise routes.
 *
 * @package Drupal\jobe_find\Controller
 */
class entrepriseController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Entreprise  revision.
   *
   * @param int $entreprise_revision
   *   The Entreprise  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($entreprise_revision) {
    $entreprise = $this->entityManager()->getStorage('entreprise')->loadRevision($entreprise_revision);
    $view_builder = $this->entityManager()->getViewBuilder('entreprise');

    return $view_builder->view($entreprise);
  }

  /**
   * Page title callback for a Entreprise  revision.
   *
   * @param int $entreprise_revision
   *   The Entreprise  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($entreprise_revision) {
    $entreprise = $this->entityManager()->getStorage('entreprise')->loadRevision($entreprise_revision);
    return $this->t('Revision of %title from %date', array('%title' => $entreprise->label(), '%date' => format_date($entreprise->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Entreprise .
   *
   * @param \Drupal\jobe_find\Entity\entrepriseInterface $entreprise
   *   A Entreprise  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(entrepriseInterface $entreprise) {
    $account = $this->currentUser();
    $langcode = $entreprise->language()->getId();
    $langname = $entreprise->language()->getName();
    $languages = $entreprise->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $entreprise_storage = $this->entityManager()->getStorage('entreprise');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $entreprise->label()]) : $this->t('Revisions for %title', ['%title' => $entreprise->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all entreprise revisions") || $account->hasPermission('administer entreprise entities')));
    $delete_permission = (($account->hasPermission("delete all entreprise revisions") || $account->hasPermission('administer entreprise entities')));

    $rows = array();

    $vids = $entreprise_storage->revisionIds($entreprise);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\jobe_find\entrepriseInterface $revision */
      $revision = $entreprise_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $entreprise->getRevisionId()) {
          $link = $this->l($date, new Url('entity.entreprise.revision', ['entreprise' => $entreprise->id(), 'entreprise_revision' => $vid]));
        }
        else {
          $link = $entreprise->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entreprise.revision_revert_translation_confirm', ['entreprise' => $entreprise->id(), 'entreprise_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entreprise.revision_revert_confirm', ['entreprise' => $entreprise->id(), 'entreprise_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entreprise.revision_delete_confirm', ['entreprise' => $entreprise->id(), 'entreprise_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['entreprise_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
