<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $items \backend\interfaces\AppleInterface[]|null */

$this->title = 'Яблочный сад';
?>
<div class="site-index">

  <div class="jumbotron">
    <h1>Райские кущи!</h1>
  </div>

  <div class="body-content">

    <div class="row">
        <?= Html::a('Генезис',
            ['genesis'],
            ['class' => 'btn btn-success', 'title' => 'Создать еще 10']) ?>
        <?= Html::a('Грехопадение: собрать урожай',
            ['fall-of-man'],
            ['class' => 'btn btn-danger', 'title' => 'Удалить все']) ?>
    </div>

    <div class="row">
      <h2>
        Список запретных плодов:
      </h2>
    </div>

    <div class="row">
        <?php
        if (!empty($items)) {
            echo $this->render('items', ['items' => $items]);
        } else { ?>
          <h3>Нет данных.</h3>
        <?php } ?>
    </div>

  </div>
</div>
