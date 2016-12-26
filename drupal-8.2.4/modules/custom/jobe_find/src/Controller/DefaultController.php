<?php

namespace Drupal\jobe_find\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 *
 * @package Drupal\jobe_find\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

  public function validate($id){


      db_query('UPDATE `__GSCOPE__entreprise_field_data` SET `status` = \'1\' WHERE `__GSCOPE__entreprise_field_data`.`id` ='.$id ) ;

      return [
          '#type' => 'markup',
          '#markup' => $this->t('validate method: with parameter(s):'.$id),
      ];
  }

}
