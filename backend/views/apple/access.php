<?php
declare(strict_types=1);

/** @var string|null $error */

$this->title = 'Получение доступа в сад';

use yii\helpers\Html; ?>
<div class="jumbotron">
  <h1><?= $this->title ?></h1>
    <?php if ($error) : ?>
      <h3><?= $error ?></h3>
    <?php endif; ?>
</div>
<div class="body-content">
  <div class="row">
    <p>Только достойные могут работать в райских кущах.</p>
    <p>Помолитесь о доступе высшим силам!</p>
  </div>
  <div class="row">
      <?php
      echo Html::beginForm(
          ['apple/get-access'],
          'post',
          ['enctype' => 'multipart/form-data']);
      echo Html::label('Введите молитву:', 'password');
      echo Html::input('password',
          'password',
          '',
          [
              'class'       => 'apple-password',
              'placeholder' => 'Введите пароль',
          ]
      );
      echo Html::input('submit', 'apple-access', 'Помолиться');

      echo Html::endForm();
      ?>
  </div>
</div>