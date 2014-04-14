<?php

/**
 * Creates a Google Plases Text Search query.
 *
 * Class QueryBuilderSearch
 */
class QueryBuilderSearchText extends QueryBuilder {

  public function __construct(PlacesApi $api) {
    array_push($this->mandatory, 'query');

    parent::__construct($api);
  }


  public function getType() {
    return "textsearch";
  }

  public function setQuery($text) {
    $this->query['query'] = $text;
    return $this;
  }

  /*** Here we could implement the other parameters that are needed ***/

}
