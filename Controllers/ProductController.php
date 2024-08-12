<?php

class ProductController extends Product
{

  public function getProduct(int $id)
  {
    return $this->get_product($id);
  }

  public function show_products()
  {

    if($_GET){

      if(isset($_GET['page'])){unset($_GET['page']);};

      $requests = $_GET;

      foreach( $requests as $key => $req){
        if($req == "all"){
          unset($_GET[$key]);
          continue;
        };
        switch ($key) {
          case 'order':
            switch ($req) {
              case 'htl':
                $order = "`price` DESC";
                break;
              case 'lth':
                $order = "`price` ASC";
                break;
              case 'name':
                $order = "`name` ASC";
                break;
              default:
                $order = "`name` ASC";
                break;
            };
            break;
          case 'category':
            $catg = new CategoryController();
            $catg->getCategory(['name' => $req]);
            $cond[] = "`category_id` = " . $catg->id; 
            break;
          case 'size':
            $cond[] = "`size` = " . $req;
            break;
          case 'color':
            $cond[] = "`color` = " . $req;
            break;
        } 
      }

    };

    $conditions = isset($cond) ? implode("&", $cond) : 1;

    $page = $_GET['page'] ?? 1;
    return $this->get_products($conditions, 20, $page);

  }

  public function get_item(int $id)
  {
    return $this->get_one_variant($id);
  }

  public function update_varitation(int $id, array $params)
  {
    $this->update_variant($id, $params);
  }

}