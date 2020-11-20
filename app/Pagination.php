<?php
class Pagination
{
  private $max = 6;
  //Текущая страница
  private $current_page;
  //Общее количество записей
  private $total;
  //записи на странице
  private $limit;

  public function __construct($total, $current_page, $limit)
  { // количество записей
    $this->total = $total;
    //количество записей на странице
    $this->limit = $limit;
    //количество страниц
    $this->amount = $this->amount();
    //номер текущей страницы
    $this->current_page = $current_page;
  }

  public function get($sortSelect,$sortOrder)
  {
      $link = null;
      $limits = $this->limits();
      $html = '<ul class="pagination">';
      for ($page=$limits[0]; $page <=$limits[1] ; $page++) {
        if(($page > 0) &&( $page<=$this->amount)){
          if($page == $this->current_page){
            $link.='<li class="active"><a href="#">'.$page.'</a></li>';
          }else {
            $link.=$this->generateHtml($page,$sortSelect,$sortOrder);
          }
        }
      }
      $html.=$link.'</ul>';
      return $html;
  }
  private function generateHtml($page,$sortSelect,$sortOrder){
    $current_URI =  rtrim($_SERVER['REQUEST_URI'],'/').'/';
    $current_URI = preg_replace ('~[0-9]+~','',$current_URI);
    return '<li><a href="/'. $page.'?sortSelect='.$sortSelect.'&sortOrder='.$sortOrder.'">'.$page. '</a></li>';

  }

  private function limits(){
    $left = $this->current_page - ceil($this->max/2);
    $right = $this->current_page + ceil($this->max/2);
    return array(
      '0'=>$left,
      '1'=>$right
    );
  }

  private function amount()
  {
      // Делим и возвращаем
      return ceil($this->total / $this->limit);
  }
}
?>
