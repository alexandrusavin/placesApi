<?php

/**
 * Parses the JSON output resulting in calling the google api.
 *
 * Class ParserJson
 */
class ParserJson extends Parser {

  /**
   * @var string Type of parser.
   */
  protected $type = "json";

  /**
   * Does the actual parsing of the JSON object.
   *
   * @throws parserException
   */
  protected function _parse() {
    $json = json_decode($this->input);

    if ($json === NULL) {
      throw new ParserException("Could not decode JSON.");
    }

    if ($json->results) {
      foreach($json->results as $result) {
        $this->output[] = $result;
      }
    }
  }

}