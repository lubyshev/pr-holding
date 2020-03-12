<?php
declare(strict_types=1);

namespace backend\controllers;

use backend\entities\AppleEntity;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AppleController extends Controller
{
    /**
     * Сколько яблок создавать автоматически.
     *
     * @var int
     */
    const AUTO_CREATE_COUNT = 10;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'genesis', 'fall-of-man'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('apple', [
            'items' => AppleEntity::findAll(),
        ]);
    }

    /**
     * Генерация яблок.
     *
     * @return void
     */
    public function actionGenesis()
    {
        $colors      = [
            AppleEntity::COLOR_GREEN,
            AppleEntity::COLOR_RED,
            AppleEntity::COLOR_YELLOW,
        ];
        $rottenReady = 3;
        $tm          = time();
        for ($i = 0; $i < self::AUTO_CREATE_COUNT; $i++) {
            $daysAgo   = rand(0, 9);
            $hoursAgo  = rand(0, 23);
            $createdAt = (new \DateTimeImmutable(date('Y-m-d H:i:s', $tm - 3600)))
                ->sub(new \DateInterval("P{$daysAgo}DT{$hoursAgo}H"));

            $item = AppleEntity::create($colors[rand(0, 2)], $createdAt);

            // Создадим несколько упавших
            if ($rottenReady) {
                $minutesLeft = 5 * $rottenReady;
                $fallAt      = (new \DateTimeImmutable())
                    ->sub(new \DateInterval("PT{$minutesLeft}M"));
                $item->fallOnGround($fallAt);
                $rottenReady--;
            }

            $item->save();
        }

        $this->redirect('/admin/apple');
    }

    /**
     * Удаление всех яблок.
     *
     * @return void
     */
    public function actionFallOfMan()
    {
        $items = AppleEntity::findAll();
        if ($items) {
            foreach ($items as $item) {
                $item->delete();
            }
        }

        $this->redirect('/admin/apple');
    }


}
