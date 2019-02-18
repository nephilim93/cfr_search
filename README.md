# Simple module to perform a MediaWiki RESTful API search using Drupal 8.

Link to GIST: https://gist.github.com/mcewand/69510cebb214184174b5cf30b5b8298e

## Challenge explanation:

### 1) Loads a page at /wiki which explains what this page does.

I created a route using the cfr_search.routing.yml file at the "/wiki" endpoint. Then assigned a function in the controller to render the content for the page.

### 2) The page should include a 'Search' form field.

The \Form\CfrSearchForm class just extends from FormBase, programatically creates the form and implements a submit handler. This is then called by the controller and rendered in the theme.

### 3) A user can either enter a value in the form field or provide a url parameter (/wiki/[parameter]).
&
### 4) If a URL parameter is provided then the page displays wikipedia articles containing the parameter in the title.

A parameter is declared in the cfr_search.routing.yml, then injected to the controller to handle the request.
To fetch the information from Wikipedia, I created a service in the cfr_search.services.yml which injects a static object of the \Client\CfrSearchClient class to our controller, allowing us to perform multiple queries to retrieve relevant articles, links and extracts.

### 5) If no parameter is provided, then the page displays wikipedia articles for the term provided in the 'Search' form field.

The form handler will execute a redirect to the route using the value from the textfield as a parameter. No need for additional logic since we're already handling this as our route param.

### 6) The page should display the term that is being searched.

There's a callback in the router file 'title_callback' which will query the controller to dinamically show the search term as the page title.

### 7) Search results should include the Title, a link to the article, and the extract for the article.

To avoid doing multiple calls to Wikipedia, I search for relevant articles first and get all the relevant IDs. I can then query Wiki a couple of times to fetch all article URLs and extracts. The mapping of all 3 requests happens inside the controller.

### 8) Your module should include functional tests and relevant documentation.

PHPUnit is now the standard for Drupal 8 testing. I created the class CfrSearchTests to implement some basic functional testing and check for page access, submit the form and get the results using the URL parameter.

### 9) Check the module into github.

Module has been checked.
