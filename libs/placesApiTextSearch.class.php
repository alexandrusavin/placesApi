<?php

/**
 * Search places by name.
 *
 * Class PlacesApiTextSearch
 */
class PlacesApiTextSearch extends PlacesApi {

  /**
   * @param $key
   */
  public function __construct($key) {
    $this->suffix = "textsearch";
    $this->builder = new QueryBuilderSearchText($this);

    parent::__construct($key);
  }

}