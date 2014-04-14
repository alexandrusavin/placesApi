<?php

/**
 * Class QueryBuilderAutocomplete
 * @package QueryBuilder
 */
class QueryBuilderAutocomplete extends QueryBuilder {

  public function __construct(PlacesApi $api) {
    array_push($this->mandatory, 'input');

    parent::__construct($api);
  }

  public function getType() {
    return "autocomplete";
  }

  public function setInput($text) {
    $this->query['input'] = $text;
    return $this;
  }

  /*** Here we could implement the other parameters that are needed ***/

}