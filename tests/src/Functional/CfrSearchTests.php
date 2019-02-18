<?php

namespace Drupal\Tests\cfr_search\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test basic functionality of cfr_search.
 *
 * @group cfr_search
 */
class CfrSearchTests extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    // Enable cfr_search module.
    'cfr_search',
  ];

  /**
   * A simple user.
   *
   * @var userobject
   */
  private $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    // Make sure to complete the normal setup steps first.
    parent::setUp();
  }

  /**
   * Test permissions against our module.
   */
  public function testForCfrSearchPermissions() {
    $assert = $this->assertSession();
    // Load the /wiki page.
    $this->drupalGet('/wiki');
    // Confirm that the anonymous user can't see the page.
    $this->assertSession()->statusCodeEquals(403);
    // Create new user and grant permission.
    $this->user = $this->drupalCreateUser([
      'access content',
    ]);
    // Log the user in.
    $this->drupalLogin($this->user);
    // Load the /wiki page.
    $this->drupalGet('/wiki');
    // Confirm that the user can visit page and it loads ok.
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Test the form functionality.
   */
  public function testForCfrSearchForm() {
    $assert = $this->assertSession();
    // Create new user and grant permission.
    $this->user = $this->drupalCreateUser([
      'access content',
    ]);
    // Log the user in.
    $this->drupalLogin($this->user);
    // Load the /wiki page.
    $this->drupalGet('/wiki');
    // Confirm that the user can visit page and it loads ok.
    $this->assertSession()->statusCodeEquals(200);
    // Fill out the search field.
    $this->getSession()->getPage()->fillField('search_term', 'Apple');
    // Submit the form.
    $this->getSession()->getPage()->pressButton('Search');
    // Check that we get the word Apple in the page.
    $this->assertSession()->pageTextContains('Apple');
  }

  /**
   * Test results.
   */
  public function testForCrfSearchResults() {
    $assert = $this->assertSession();
    // Create new user and grant permission.
    $this->user = $this->drupalCreateUser([
      'access content',
    ]);
    // Log the user in.
    $this->drupalLogin($this->user);
    // Load the /wiki page.
    $this->drupalGet('/wiki/Orange');
    // Confirm that the user can visit page and it loads ok.
    $this->assertSession()->statusCodeEquals(200);
    // Check that a sample of the first result for Orange extract exists.
    $this->assertSession()->pageTextContains('Orange usually refers to: Orange (colour), occurs between red and yellow in the visible spectrum');
  }

}
