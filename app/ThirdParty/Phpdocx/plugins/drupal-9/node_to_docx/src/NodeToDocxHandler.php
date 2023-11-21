<?php

/**
 * @file
 * Contains \Drupal\node_to_docx\NodeToDocxHandler.
 */

namespace Drupal\node_to_docx;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NodeToDocxHandler implements ContainerAwareInterface {
  use ContainerAwareTrait;

  /**
   *  Generates a docx file adding the content of a node.
   *
   *  @param \Drupal\node\NodeInterface $node
   *   The node to be processed.
   *  @return RedirectResponse
   */
  public function convertToDocx(NodeInterface $node) {
    // check if phpdocx library is available
    if ($this->isPhpdocxLibraryAvailable() === TRUE) {
      $filename = $node->id() . '-' . $node->getTitle();
      $view = \Drupal::entityTypeManager()->getViewBuilder('node')->view($node, 'node_to_docx');
      $view['#theme'] = 'node_to_docx';
      $drupalMarkup = \Drupal::service('renderer')->render($view);
      $this->generateDocxFromHtml($drupalMarkup->__toString(), $filename);
      return new RedirectResponse(Url::fromRoute('entity.node.canonical', array('node' => $node->id()))->toString());
    } else {
      $messenger = \Drupal::messenger();
      $messenger->addMessage(t('Phpdocx library is not included. Please copy phpdocx to the libraries directory or the module directory.'), $messenger::TYPE_WARNING);
      return new RedirectResponse(Url::fromRoute('entity.node.canonical', array('node' => $node->id()))->toString());
    }
  }

  /**
   *  Checks if phpdocx library is available.
   *  @return boolean
   */
  public function isPhpdocxLibraryAvailable() {
    // check if CreateDocx class can be instantiated
    if (class_exists('Phpdocx\\Create\\CreateDocx')) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   *  Generates a docx file from the html code.
   *
   *  @param string $node
   *   The html code to be processed.
   *  @param string $node
   *   The file name to the output.
   *  @return docx file response
   */
  private function generateDocxFromHtml($html, $file_name_output) {
    $docx = new \Phpdocx\Create\CreateDocx();
    $docx->embedHTML($html);

    $file_path = \Drupal::service('file_system')->realpath(\Drupal::config('system.file')->get('default_scheme') . '://');
    $docx->createDocx($file_path . '/' . $file_name_output);
    $buffer = file_get_contents($file_path . '/' . $file_name_output . '.docx');
    header('Content-Description: File Transfer');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Pragma: public');
    header('Expires: Sat, 1 Jan 1970 01:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Type: application/force-download');
    header('Content-Type: application/octet-stream', false);
    header('Content-Type: application/download', false);
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document', false);
    if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        header('Content-Length: ' . strlen($buffer));
    }
    header('Content-disposition: attachment; filename="' . $file_name_output . '.docx"');
    echo $buffer;

    exit;
  }
}