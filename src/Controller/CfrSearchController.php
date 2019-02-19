<?php

namespace Drupal\cfr_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CfrSeachClient.
 *
 * @package Drupal\cfr_search\Controller
 */
class CfrSearchController extends ControllerBase {
  /**
   * The CFR Search Client.
   *
   * @var \Drupal\cfr_search\Client\CfrSearchClient
   */
  protected $cfrSearchClient;

  /**
   * CfrSearch constructor.
   *
   * @param \Drupal\cfr_search\CfrSearchClient $cfr_search_client
   *   The Client object injection.
   */
  public function __construct($cfr_search_client) {
    $this->cfrSearchClient = $cfr_search_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cfr_search_client')
    );
  }

  /**
   * Callback function to set the search term as the page title.
   *
   * @param string $search_term
   *   The search term.
   *
   * @return string
   *   The page title.
   */
  public function getTitle($search_term) {
    return $search_term !== '' ? urldecode($search_term) : 'CFR RESTful Search';
  }

  /**
   * Fetch query results from client using the search term.
   *
   * @param string $search_term
   *   The term to search.
   *
   * @todo
   *    Find a proper way to link to the content. Search by text won't
   *    provide a URL, but there needs to be a proper way to fetch
   *    both items without making 2 separate calls
   *    I.E. Opentext search
   */
  public function searchArticles($search_term) {
    // Search for the term using the client connection class.
    $search_results = $this->cfrSearchClient->searchArticles($search_term);
    // Initialize empty array to store search results.
    $results = [];
    // Initialize empty array to store page ids.
    $page_ids = [];
    // Check if there's results from the Client class.
    if (isset($search_results['query']['search'])) {
      // Parse results to fetch proper links and create a themeable array.
      foreach ($search_results['query']['search'] as $key => $search_result) {
        $results[] = [
          '#theme' => 'cfr_search_article_preview',
          '#title' => $search_result['title'],
          '#teaser' => '',
          '#link' => '',
          '#page_id' => $search_result['pageid'],
        ];
        array_push($page_ids, $search_result['pageid']);
      }
      // Fetch Article URLs and extracts using the page_ids array.
      $pages = $this->cfrSearchClient->getArticleUrlsExtracts($page_ids);
      // Map URLs and Extracts to links and teasers in the results[] array.
      foreach ($results as $key => $result) {
        // Fetch property value.
        $hash = $result['#page_id'];
        // Set proper link.
        $results[$key]['#link'] = $pages['query']['pages'][$hash]['fullurl'];
        // Set the extract as a teaser.
        $results[$key]['#teaser'] = $pages['query']['pages'][$hash]['extract'];
      }
    }
    // Fetch the search form.
    $form = $this->formBuilder()->getForm('Drupal\cfr_search\Form\CfrSearchForm');
    // Theme main page content.
    $content[] = [
      '#theme' => 'cfr_search',
      '#form' => $form,
      '#results' => $results,
    ];
    return $content;
  }

}
