<?php

/**
 * Class Parser
 *
 * Used to parse the response from calling the google api.
 */
abstract class Parser {

  /**
   * Can be xml or json.
   *
   * @var string
   */
  protected $type;

  /**
   * Raw input from the placesApi
   *
   * @var string
   */
  protected $input;

  /**
   * Parsed output formatted as array of results.
   *
   * @var mixed
   */
  protected $output;

  /**
   * This method has to be implemented by all the children of this class.
   * It does the actual parsing from the taw input to the formatted output.
   *
   * @return mixed
   */
  abstract protected function _parse();

  /**
   * @param $input
   *
   * @return $this
   */
  public function setInput($input) {
    $this->input = $input;

    return $this;
  }

  /**
   * Returns the type of the parser.
   *
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Returns the parsed output.
   *
   * @return mixed
   */
  public function getOutput() {
    $this->_parse();

    return $this->output;
  }

}

/**
 * Class ParserException
 */
class ParserException extends Exception {}
