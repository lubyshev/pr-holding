<?php
declare(strict_types=1);

use backend\interfaces\AppleInterface;
use yii\helpers\Html;

/* @var $item \backend\interfaces\AppleInterface */

switch ($item->getColor()) {
    case AppleInterface::COLOR_GREEN:
        $colorClass = 'apple-green';
        break;
    case AppleInterface::COLOR_RED:
        $colorClass = 'apple-red';
        break;
    case AppleInterface::COLOR_YELLOW:
        $colorClass = 'apple-yellow';
        break;
}

$fallAt = '';
if (!$item->isOnTree()) {
    $fallAt = $item->fallAt()->format('Y-m-d H:i:s');
}
$createdAt = $item->createdAt()->format('Y-m-d H:i:s');

$size = number_format($item->getSize() * 100, 4);

?>
<tr>
  <td><?= $item->getId() ?></td>
  <td class="<?= $colorClass ?>"></td>
  <td class="apple-created"><?= $createdAt ?></td>
  <td class="apple-fall"><?= $fallAt ?></td>
  <td class="apple-state"><?= $item->isOnTree() ? 'X' : '' ?></td>
  <td class="apple-state"><?= $item->isOnGround() ? 'X' : '' ?></td>
  <td class="apple-state"><?= $item->isRotten() ? 'X' : '' ?></td>
  <td class="apple-size"><?= $size ?></td>
  <td class="apple-form">
      <?php
      if (!$item->isRotten()) {
          echo Html::beginForm(
              ['apple/update', 'id' => $item->getId()],
              'post',
              ['enctype' => 'multipart/form-data']);
          if ($item->isOnTree()) {
              echo Html::input('submit', 'apple-fall', 'Сбросить');
          }
          if ($item->isOnGround()) {
              echo Html::input('number',
                  'apple-eat-value',
                  '0',
                  [
                      'class' => 'apple-eat',
                      'min'   => 0,
                      'max'   => 100,
                  ]
              );
              echo Html::input('submit', 'apple-eat', 'Откусить');
          }

          echo Html::endForm();
      }
      ?>
  </td>
</tr>
