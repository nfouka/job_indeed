<?php

/**
 * @file
 * Theme settings.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormSubmitter;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Query\Select;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function space_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
  // Attach the color picker.
  $form['#attached']['library'][] = 'space/space-settings';

  $form['space_settings'] = array(
    '#type' => 'details',
    '#title' => t('Space Theme Settings'),
    '#open' => TRUE,
  );

  $form['space_settings']['main_header'] = array(
    '#type' => 'details',
    '#title' => t('Main Header Settings'),
  );

  $form['space_settings']['main_header']['main_header_media'] = array(
    '#type' => 'managed_file',
    '#title' => t('Main Header Image/Video'),
    '#description' => t('The uploaded image or video will be displayed on the front page in the header. If no image or video is selected, the default image will be used.'),
    '#default_value' => theme_get_setting('main_header_media'),
    '#progress_message' => t('Please wait...'),
    '#progress_indicator' => 'bar',
    '#upload_location' => 'public://space/',
    '#upload_validators' => array(
      'file_validate_extensions' => array('gif png jpg jpeg svg mp4 webm'),
    ),
  );

  $form['space_settings']['main_header']['media_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('File is a video'),
    '#description' => t('Check this box if the file you uploaded is a video and not an image.'),
    '#default_value' => theme_get_setting('media_type'),
  );

  $form['space_settings']['main_header']['video_settings'] = array(
    '#type' => 'details',
    '#title' => t('Video Settings'),
    '#open' => TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="media_type"]' => array('checked' => TRUE),
      ),
    ),
  );

  $form['space_settings']['main_header']['video_settings']['header_video_still'] = array(
    '#type' => 'managed_file',
    '#title' => t('Video Still'),
    '#description' => t('The image to show if the browser doesn\'t support videos or if on a smaller device (video isn\'t accepted by default).'),
    '#default_value' => theme_get_setting('header_video_still'),
    '#progress_message' => t('Please wait...'),
    '#progress_indicator' => 'bar',
    '#upload_location' => 'public://space/',
    '#upload_validators' => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
    ),
  );

  // Create a form to hold the current main header fid in case it is deleted.
  $form['space_settings']['main_header']['main_header_hidden'] = array(
    '#type' => 'hidden',
    '#value' => theme_get_setting('main_header_media'),
  );

  // Create a form to hold the current video image fid in case it is deleted.
  $form['space_settings']['main_header']['header_video_still_hidden'] = array(
    '#type' => 'hidden',
    '#value' => theme_get_setting('header_video_still'),
  );

  $form['space_settings']['main_header']['main_header_screen'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show screen effect'),
    '#description' => t('Makes the main header image/video appear as if it is behind a screen.'),
    '#default_value' => theme_get_setting('main_header_screen') !== NULL ? theme_get_setting('main_header_screen') : 1,
  );

  $form['space_settings']['main_header']['main_header_fade'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show fade effect'),
    '#description' => t('Adds a fade to the header image/video to blend into the body.'),
    '#default_value' => theme_get_setting('main_header_fade') !== NULL ? theme_get_setting('main_header_fade') : 1,
  );

  // Overlay settings.
  $form['space_settings']['main_header']['main_header_overlay'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show an overlay'),
    '#description' => t('Adds an overlay over the image/video.'),
    '#default_value' => theme_get_setting('main_header_overlay') !== NULL ? theme_get_setting('main_header_overlay') : 1,
  );

  $form['space_settings']['main_header']['overlay_settings'] = array(
    '#type' => 'details',
    '#title' => t('Overlay Settings'),
    '#open' => TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="main_header_overlay"]' => array('checked' => TRUE),
      ),
    ),
  );

  $form['space_settings']['main_header']['overlay_settings']['opacity'] = array(
    '#type' => 'textfield',
    '#size' => 4,
    '#maxlength' => 4,
    '#title' => t('Opacity'),
    '#description' => t('The opacity of the overlay. 0 = transparent, 1 = opaque.'),
    '#default_value' => theme_get_setting('opacity') !== NULL ? theme_get_setting('opacity') : 0.5,
  );

  $form['space_settings']['main_header']['overlay_settings']['color_picker'] = array(
    '#type' => 'textfield',
    '#title' => t('Overlay Color'),
    '#size' => 18,
    '#maxlength' => 18,
    '#attributes' => array(
      'id' => 'color-picker',
    ),
    '#description' => t('Enter the overlay color in rgb. Ex: rgb(255, 200, 187)'),
    '#default_value' => theme_get_setting('color_picker') !== NULL ? theme_get_setting('color_picker') : "rgb(255, 255, 255)",
  );

  $form['space_settings']['social_media'] = array(
    '#type' => 'details',
    '#title' => t('Social Media Icons'),
    '#open' => FALSE,
    '#description' => t('Please enter the full URL to each of the sites. Any field left blank will not show an icon.'),
  );

  $form['space_settings']['social_media']['space_facebook'] = array(
    '#type' => 'textfield',
    '#title' => t('Facebook'),
    '#default_value' => theme_get_setting('space_facebook') !== NULL ? theme_get_setting('space_facebook') : '',
  );

  $form['space_settings']['social_media']['space_twitter'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter'),
    '#default_value' => theme_get_setting('space_twitter') !== NULL ? theme_get_setting('space_twitter') : '',
  );

  $form['space_settings']['social_media']['space_linkedin'] = array(
    '#type' => 'textfield',
    '#title' => t('LinkedIn'),
    '#default_value' => theme_get_setting('space_linkedin') !== NULL ? theme_get_setting('space_linkedin') : '',
  );

  $form['space_settings']['social_media']['space_codepen'] = array(
    '#type' => 'textfield',
    '#title' => t('Codepen'),
    '#default_value' => theme_get_setting('space_codepen') !== NULL ? theme_get_setting('space_codepen') : '',
  );

  $form['space_settings']['social_media']['space_bitbucket'] = array(
    '#type' => 'textfield',
    '#title' => t('Bitbucket'),
    '#default_value' => theme_get_setting('space_bitbucket') !== NULL ? theme_get_setting('space_bitbucket') : '',
  );

  $form['space_settings']['social_media']['space_github'] = array(
    '#type' => 'textfield',
    '#title' => t('GitHub'),
    '#default_value' => theme_get_setting('space_github') !== NULL ? theme_get_setting('space_github') : '',
  );

  $form['space_settings']['social_media']['space_stack_overflow'] = array(
    '#type' => 'textfield',
    '#title' => t('Stack Overflow'),
    '#default_value' => theme_get_setting('space_stack_overflow') !== NULL ? theme_get_setting('space_stack_overflow') : '',
  );

  // Build region array.
  $regions = array(
    'content' => 'Content',
    'content_2' => 'Content 2',
    'content_3' => 'Content 3',
    'content_4' => 'Content 4',
    'content_5' => 'Content 5',
  );

  $form['space_settings']['backgrounds'] = array(
    '#type' => 'details',
    '#title' => t('Region Background Settings'),
    '#open' => FALSE,
    '#description' => t('Fixed background Images that will show in the region. This is useful to add title effects to the page. See demo site for examples.'),
  );

  foreach ($regions as $key => $region) {
    $form['space_settings']['backgrounds']['region_' . $key] = array(
      '#type' => 'checkbox',
      '#title' => $region,
      '#default_value' => theme_get_setting('region_' . $key),
    );

    // Region Content Background Options
    $form['space_settings']['backgrounds']['region_' . $key . '_fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('@region Region Options', array('@region' => $region)),
      '#states' => array(
        'visible' => array(
          ':input[name="region_' . $key . '"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['space_settings']['backgrounds']['region_' . $key . '_fieldset']['region_' . $key . '_image'] = array(
      '#type' => 'managed_file',
      '#title' => t('@region Region Image', array('@region' => $region)),
      '#description' => t('The uploaded image will be displayed on the front page in the header. If nothing is selected, the default image will be used.'),
      '#default_value' => theme_get_setting('region_' . $key . '_image'),
      '#progress_message' => t('Please wait...'),
      '#progress_indicator' => 'bar',
      '#upload_location' => 'public://space/',
      '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg svg'),
      ),
    );

    // Create a form to hold the current region fid in case it is deleted.
    $form['space_settings']['region_' . $key . '_hidden'] = array(
      '#type' => 'hidden',
      '#value' => theme_get_setting('region_' . $key . '_image'),
    );

    $form['space_settings']['backgrounds']['region_' . $key . '_fieldset']['region_' . $key . '_front'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show background on front page only.'),
      '#default_value' => theme_get_setting('region_' . $key . '_front'),
    );

    $form['space_settings']['backgrounds']['region_' . $key . '_fieldset']['region_' . $key . '_screen'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show screen effect.'),
      '#default_value' => theme_get_setting('region_' . $key . '_screen'),
    );
  }

  // Odd issue in Drupal where the submit handler isn't called. Workaround.
  $theme_settings_path = drupal_get_path('theme', 'space') . '/theme-settings.php';
  $info = $form_state->getBuildInfo();

  if (file_exists($theme_settings_path) && !in_array($theme_settings_path, $info['files'])) {
    $info['files'][] = $theme_settings_path;
    $form_state->setBuildInfo($info);
  }

  // Add the submit handler.
  $form['#submit'][] = 'space_theme_settings_submit';
}

/**
 * Submit handler to save the managed file permanently.
 */
function space_theme_settings_submit($form, FormStateInterface $form_state) {
  // Only run when the submit button is pushed (AJAX will call this function).
  $triggering_element = $form_state->getTriggeringElement();
  if ($triggering_element['#id'] == 'edit-submit') {
    // Get the fid from the submitted form.
    if ($form_state->getValue('main_header_media', 0)) {
      $fid = $form_state->getValue(array('main_header_media', 0));
    }

    // Check to see if an fid was found.
    if (isset($fid)) {
      // Load the file.
      $file = File::load($fid);

      if (is_object($file) && !$file->isPermanent()) {
        // Change the file status to be permanent.
        $file->setPermanent();

        // Save the file.
        $file->save();

        // Add file usage.
        \Drupal::service('file.usage')->add($file, 'space', 'theme', $fid, 1);
      }
    }

    // If the image has been removed, remove it from the database.
    if ($form_state->isValueEmpty('main_header_media') || $form_state->getValue(array('main_header_media', 0)) != $form_state->getValue(array('main_header_hidden', 0))) {
      // Load the current existing file.
      $fid = $form_state->getValue(array('main_header_hidden', 0));
      if ($fid) {
        // Load the file.
        $file = File::load($fid);

        // If the file exists, delete it.
        if (is_object($file)) {
          $file->delete();
        }
      }
    }

    // Get the fid for the video image still.
    if ($form_state->getValue('header_video_still', 0)) {
      $fid = $form_state->getValue(array('header_video_still', 0));
    }

    // Check to see if an fid was found.
    if (isset($fid)) {
      // Load the file.
      $file = File::load($fid);

      if (is_object($file) && !$file->isPermanent()) {
        // Change the file status to be permanent.
        $file->setPermanent();

        // Save the file.
        $file->save();

        // Add file usage.
        \Drupal::service('file.usage')->add($file, 'space', 'theme', $fid, 1);
      }
    }

    // If the image has been removed, remove it from the database.
    if ($form_state->isValueEmpty('header_video_still') || $form_state->getValue(array('header_video_still', 0)) != $form_state->getValue(array('header_video_still_hidden', 0))) {
      // Load the current existing file.
      $fid = $form_state->getValue(array('header_video_still_hidden', 0));
      if ($fid) {
        // Load the file.
        $file = File::load($fid);

        // If the file exists, delete it.
        if (is_object($file)) {
          $file->delete();
        }
      }
    }

    // Build region array.
    $regions = array(
      'content' => 'Content',
      'content_2' => 'Content 2',
      'content_3' => 'Content 3',
      'content_4' => 'Content 4',
      'content_5' => 'Content 5',
    );

    // Loop through each region and check for images. If set, make permanent.
    foreach ($regions as $key => $region) {
      if ($form_state->getValue(array('region_' . $key . '_image', 0))) {
        $fid = $form_state->getValue(array('region_' . $key . '_image', 0));
      }

      // Check to see if an fid was found.
      if (isset($fid)) {
        // Load the file.
        $file = File::load($fid);

        if (is_object($file) && !$file->isPermanent()) {
          // Change the file status to be permanent.
          $file->setPermanent();

          // Save the file.
          $file->save();

          // Add file usage.
          \Drupal::service('file.usage')->add($file, 'space', 'theme', $fid, 1);
        }
      }

      // If the image has been removed, remove it from the database.
      if ($form_state->isValueEmpty('region_' . $key . '_image') || $form_state->getValue(array('region_' . $key . '_image', 0)) != $form_state->getValue(array('region_' . $key . '_hidden', 0))) {
        // Load the current existing file.
        $fid = $form_state->getValue(array('region_' . $key . '_hidden', 0));
        if ($fid) {
          // Load the file.
          $file = File::load($fid);

          // If the file exists, delete it.
          if (is_object($file)) {
            $file->delete();
          }
        }
      }
    }
  }
}
