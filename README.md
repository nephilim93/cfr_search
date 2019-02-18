# Simple module to perform a MediaWiki RESTful API search using Drupal 8.

Link to GIST: https://gist.github.com/mcewand/69510cebb214184174b5cf30b5b8298e

## Challenge explanation:

I pretty much created the module using standard Drupal techniques: 

- Create a route to /wiki in the cfr_search.routing.yml file.
- Set our search term as a url parameter. 
- Process parameter and fetch results in the \Controller\CfrSearchController.php::searchArticles function.
- Display and style results using twigs.

To fetch the information from Wikipedia, I created a service in the cfr_search.services.yml which injects a static object of the \Client\CfrSearchClient class to our controller, allowing us to perform multiple queries to retrieve relevant articles, links and extracts.

The \Form\CfrSearchForm class just extends from FormBase, programatically creates the form and implements a handler that executes a redirect to the route using the value from the textfield as a parameter.
