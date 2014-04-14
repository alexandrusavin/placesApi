<?php

/**
 * Abstract class to help into building the queries to Google Places API.
 *
 * Class QueryBuilder
 */
abstract class QueryBuilder {

  /**
   * @var array Represents the query to be executed on the API.
   */
  protected $query = array();

  /**
   * @var string
   */
  protected $prefix = "";

  /**
   * Calling build will return the Api object.
   *
   * @var PlacesApi
   */
  protected $api;

  /**
   * @var array Mandatory query properties.
   */
  protected $mandatory = array('key', 'sensor');

  abstract public function getType();

  public function __construct(PlacesApi $api) {
    $this->api = $api;
  }

  /**
   * @param $key String Authentication Key.
   *
   * @return QueryBuilder
   */
  public function setKey($key) {
    $this->query['key'] = $key;

    return $this;
  }

  /**
   * @param $sensor Boolean Indicates whether or not the Place request came from a device using a location sensor.
   *
   * @return QueryBuilder
   */
  public function setSensor($sensor = false) {
    $this->query['sensor'] = ($sensor ? 'true' : 'false');

    return $this;
  }

  /**
   * Prepares the URL query.
   *
   * @throws QueryBuilderException
   */
  private function _build() {
    // Test to see if all mandatory params are set
    $diff = array_diff($this->mandatory, array_keys($this->query));

    // If there are differences than we throw an exception.
    if (count($diff) > 0) {
      $diff_keys = implode(", ", $diff);

      throw new \QueryBuilderException("Mandatory query parameters (" . $diff_keys . ") are not set.");
    }

    $query = $this->query;

    // Walk the array, url-encode each value and prepare it for the concatenation
    array_walk($query, function (&$value, $key) {
      $value = $key . "=" . urlencode($value);
    });

    // Concatenate all params by ampersand
    return implode("&", $query);
  }

  public function build() {
    return $this->_build();
  }

  public function getQuery() {
    return $this->query;
  }

  /**
   * Magic method to transform the query array into an URL string query.
   *
   * @return string
   */
  public function __toString() {
    return $this->_build();
  }

}


class QueryBuilderException extends Exception {}
