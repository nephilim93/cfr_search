<?php

namespace Drupal\cfr_search\Client;

use Drupal\Component\Serialization\Json;

/**
 * Class CfrSeachClient.
 *
 * @package Drupal\cfr_search\Client
 */
class CfrSearchClient {
  /**
   * The Guzzle HTTP Client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * CfrSearchClient constructor.
   *
   * @param \Drupal\Core\Http\ClientFactory $http_client_factory
   *   The client factory interface.
   */
  public function __construct($http_client_factory) {
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => 'https://en.wikipedia.org/w/api.php',
    ]);
  }

  /**
   * Query the Wikipedia API for Articles that contain the search term.
   *
   * @param string $search_term
   *   The term to search.
   *
   * @return array
   *   The result object.
   *
   * @throws \GuzzleHttp\Exception\RequestException
   *    In case request fails or breaks.
   *
   * @todo
   *    Implement pagination using srlimit and sroffset parameters.
   */
  public function searchArticles($search_term = '') {
    try {
      // Map request as query object. Search by title is currently disabled.
      // More info: https://www.mediawiki.org/wiki/API:Search
      $response = $this->client->get('', [
        'query' => [
          'action' => 'query',
          'list' => 'search',
          'utf8' => '',
          'prop' => 'info',
          'format' => 'json',
          'srwhat' => 'text',
          'srsearch' => $search_term,
          'srlimit' => 20,
          'sroffset' => 0,
        ],
      ]);
      $data = Json::decode($response->getBody());
      return $data;
    }
    catch (RequestException $e) {
      // Notify user about failed request.
      drupal_set_message(t('An error ocurred while attempting to fetch information from resource: "%error"', ['%error' => $e->getMessage()]), 'error');
    }
  }

  /**
   * Get the Wikipedia Article URLs and Extracts from its page ids.
   *
   * @param array $page_ids
   *   The pageids of the Articles.
   *
   * @return Object
   *   The search results.
   *
   * @throws \GuzzleHttp\Exception\RequestException
   *    In case request fails or breaks.
   */
  public function getArticleUrlsExtracts(array $page_ids) {
    try {
      // Map request as query object.
      $response = $this->client->get('', [
        'query' => [
          'action' => 'query',
          'prop' => 'info|extracts',
          'inprop' => 'url',
          'utf8' => '',
          'format' => 'json',
          'exintro' => '',
          'explaintext' => '',
          'pageids' => implode('|', $page_ids),
        ],
      ]);
      $data = Json::decode($response->getBody());
      return $data;
    }
    catch (RequestException $e) {
      // Notify user about failed request.
      drupal_set_message(t('An error ocurred while attempting to fetch information from resource: "%error"', ['%error' => $e->getMessage()]), 'error');
    }
  }

}
