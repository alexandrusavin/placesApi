<?php

/**
 * Main class that creates and returns the results of Google Places API.
 *
 * To use it one would create a chain method request and at the end call execute.
 */
abstract class PlacesApi {
  /**
   * @var Parser
   */
  protected $parser;

  /**
   * @var QueryBuilder
   */
  protected $builder;

  /**
   * Api URL
   */
  const API_URL = "https://maps.googleapis.com/maps/api/place";

  /**
   * @var string Suffix of the api call (ex: textsearch, autocomplete)
   */
  protected $suffix;

  /**
   *
   */
  public function __construct($key) {
    $this->builder->setKey($key);
  }

  /**
   * Get the query builder.
   *
   * @return QueryBuilder
   */
  public function builder() {
    return $this->builder;
  }

  /**
   * Executes the query.
   *
   * @return mixed
   *
   * @throws PlacesApiException
   */
  protected function _execute() {
    if (!isset($this->suffix)) {
      throw new PlacesApiException("In order to initialize a query you need to set the suffix query type.");
    }

    $apiURL = $this::API_URL . '/' . $this->suffix . '/' . $this->parser->getType() . '?' . $this->builder;

    $result = $this->_call($apiURL);

    return $this->parser->setInput($result)->getOutput();
  }

  /**
   * Use the JSON parser.
   *
   * @return mixed
   */
  public function executeJSON() {
    $this->parser = new ParserJson();

    return $this->_execute();
  }

  /**
   * Use the XML parser.
   *
   * @return mixed
   */
  public function executeXML() {
    $this->parser = new ParserXML();

    return $this->_execute();
  }

  /**
   * Call the Google API.
   *
   * @param $apiUrl
   * @param int $retry
   *
   * @return mixed|string
   * @throws PlacesApiConnectException
   */
  private function _call($apiUrl, $retry = 0) {

    if (function_exists("curl_version")) {
      $ch = curl_init($apiUrl);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Challenge App for Aeria Games');

      $response = curl_exec($ch);

      $error_code = curl_errno($ch);

      if ($error_code !== 0) {
        if ($error_code === 28) {
          // We got CURLE_OPERATION_TIMEDOUT which means we could try again 3 times
          if ($retry < 3) {
            curl_close($ch);
            $retry++;
            $this->_call($apiUrl, $retry);
          } else {
            curl_close($ch);
            throw new PlacesApiConnectException("Google API timed out 3 times.");
          }
        } else {
          $error = curl_error($ch);
          throw new PlacesApiConnectException("Could not make the request. Error: " . $error);
        }
      }
    } else {
      $opts = array(
          'http' => array(
              'method' => 'GET',
              'user_agent' => 'Challenge App for Aeria Games',
              'header'=>"Accept-language: en\r\n",
              'timeout' => 120,
          )
      );

      $context = stream_context_create($opts);

      $response = @file_get_contents($apiUrl, false, $context);

      if ($response === false) {
        throw new PlacesApiConnectException("Unknown error occurred while making the request.");
      }
    }

    return $response;
  }
}

/**
 * Class PlacesApiException
 */
class PlacesApiException extends Exception {}

/**
 * Class PlacesApiConnectException
 */
class PlacesApiConnectException extends Exception {}
