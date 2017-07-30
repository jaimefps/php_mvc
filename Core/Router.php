<?php

/*
* Rotuer 
*
* Decides what controller to pass user intention to.
*/
class Router 
{

  /*
  * Associative array of routes (a.k.a. Routing Table):
  * @var array
  */
  protected $routes = [];

  /*
  * Parameters from the matched route.
  * @var array
  */
  protected $params = [];

  /*
  * Adds a route to the routing table.
  *
  * @param string $route: the route URL
  * @param array $params Parameters (controller, action, etc)
  *
  * @return void.
  */
  public function add($route, $params = [])
  {
    // Convert the route to a regular expression: escape forward slashes
    $route = preg_replace('/\//', '\\/', $route);

    // Convert variables e.g. {controller}
    $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

    // Convert variables with custom regular expressions e.g. {id:\d+}
    $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

    //  Add start and end delimiters, and case sensitive flag:
    $route = '/^' . $route . '$/i';

    $this->routes[$route] = $params;
  }


  /*
  * Get all the routes from the routing table.
  *
  * @return array.
  */
  public function getRoutes() 
  {
    return $this->routes;
  }

  /*
  * Match the route to the orutes in the routing table, settinf the $params
  * property if a route is found
  *
  * @param string $url: The route URL
  * @return boolean true if a match is found, false otherwise.
  */
  public function match($url) 
  {
    foreach ($this->routes as $route => $params) {
      if (preg_match($route, $url, $matches)) {
        foreach($matches as $key => $match) {
          if(is_string($key)) {
            $params[$key] = $match;
          }
        }
      }
      $this->params = $params;
      return true;
    }
    return false;
  }

  /*
  * Get the currently match parameters.
  *
  * @return array.
  */
  public function getParams() 
  {
    return $this->params;
  }

}

?>