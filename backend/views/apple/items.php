<?php
declare(strict_types=1);

/* @var $items \backend\interfaces\AppleInterface[] */
?>
<table class="table table-striped table-bordered apples-table">
  <thead>
  <tr>
    <th rowspan="2">Id</th>
    <th rowspan="2">Цвет</th>
    <th rowspan="2">Создано</th>
    <th rowspan="2">Упало</th>
    <th colspan="3">Состояние</th>
    <th rowspan="2">Остаток (%%)</th>
    <th rowspan="2">Действие</th>
  </tr>
  <tr>
    <th>На дереве</th>
    <th>На земле</th>
    <th>Испорчено</th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach ($items as $item) {
      echo $this->render('item', ['item' => $item]);
  }
  ?>
  </tbody>
</table>