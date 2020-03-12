<?php

/* @var $this yii\web\View */
/* @var $items array|null */

$this->title = 'Яблочный сад';
?>
<div class="site-index">

  <div class="jumbotron">
    <h1>Райские кущи!</h1>
  </div>

  <div class="body-content">

    <div class="row">
      <a
          class="btn btn-default"
          href="/admin/apple/genesis"
          title="Создать еще 10"
      >Генезис &raquo;</a>
      <a
          class="btn btn-default"
          href="/admin/apple/fall-of-man"
          title="Собрать весь урожай"
      >Грехопадение &raquo;</a>
    </div>

    <div class="row">
      <h2>
        Список запретных плодов:
      </h2>
    </div>

    <div class="row">
        <?php if (!empty($items)): ?>
            <?php
            foreach ($items as $item) {
                echo $this->render('item', ['item' => $item]);
            }
            ?>
        <?php else: ?>
          <h3>Нет данных.</h3>
        <?php endif; ?>
    </div>

  </div>
</div>
