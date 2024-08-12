<?php

class CategoryController extends Category
{

  public function getCategory(array $cond)
  {
    $this->get_category($cond);
  }

  public function getCategories()
  {
    return $this->get_categories();
  }

}