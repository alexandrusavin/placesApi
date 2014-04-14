<?php
if (function_exists("xdebug_get_code_coverage")) {
  xdebug_disable();
}

define('QUERY_TEXTSEARCH', '/textsearch');
define('QUERY_AUTOCOMPLETE', '/autocomplete');

if ($_SERVER['REQUEST_METHOD'] !== "GET" ||
    ($_SERVER['PATH_INFO'] !== QUERY_TEXTSEARCH && $_SERVER['PATH_INFO'] !== QUERY_AUTOCOMPLETE)) {
  httpResponse(array(
      'error_code' => 404,
      'error_message' => "Request not found."
  ), 404, "Not found");
}

require "./bootstraps.php";

$query_string = $_GET['query'];

try {
  switch($_SERVER['PATH_INFO']) {
    case QUERY_TEXTSEARCH:
      $query = new PlacesApiTextSearch(GOOGLE_LOCATION_API_KEY);
      $query->builder()
          ->setSensor(false)
          ->setQuery($query_string)
          ->build();

      $results = $query->executeJSON();

      $response = array();
      foreach ($results as $result) {
        $response[] = array(
            'name' => $result->name,
            'address' => $result->formatted_address
        );
      }
      httpResponse($response);

      break;

    case QUERY_AUTOCOMPLETE:
      $query = new PlacesApiAutocomplete(GOOGLE_LOCATION_API_KEY);

      $query->builder()
        ->setSensor(false)
        ->setInput($query_string)
        ->build();

      $results = $query->executeJSON();

      $response = array();
      foreach ($results as $result) {
        $response[] = array(
            'address' => $result->formatted_address
        );
      }
      httpResponse($response);

      break;
  }
} catch (PlacesApiConnectException $e) {
  // treat the connection issue
  httpResponse(array(
      'error_code' => 500,
      'error_message' => $e->getMessage()
  ), 500, $e->getMessage());

} catch (PlacesApiException $e) {
  // treat api error
  httpResponse(array(
      'error_code' => 500,
      'error_message' => $e->getMessage()
  ), 500, $e->getMessage());

} catch (ParserException $e) {
  // treat parser exception
  httpResponse(array(
      'error_code' => 500,
      'error_message' => $e->getMessage()
  ), 500, $e->getMessage());

}

function httpResponse($body, $code = 200, $error = 'OK!') {
  header("HTTP/1.0 ".$code." ".$error);
  header("Content-Type: application/json");

  if (is_array($body)) {
    echo json_encode($body);
  } else {
    echo $body;
  }

  exit();
}
