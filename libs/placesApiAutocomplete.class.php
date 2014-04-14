<?php

/**
 * Search places by name.
 *
 * Class PlacesApiTextSearch
 */
class PlacesApiAutocomplete extends PlacesApi {

  /**
   * @param $key
   */
  public function __construct($key) {
    $this->suffix = "textsearch";
    $this->builder = new QueryBuilderAutocomplete($this);

    parent::__construct($key);
  }

}