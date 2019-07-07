<?php


namespace console\controllers;

use console\models\Categories;
use yii\console\Controller;
use yii\db\Query;


class AddUrlController extends Controller {

    public function actionMake() {

        $db = new Query();

        $db->createCommand()->renameColumn('categories', 'url', 'shortUrl')->execute();
        $db->createCommand()->addColumn('categories', 'fullUrl', 'string')->execute();

        $all = Categories::getAll();

        foreach ($all as $item) {

            if ($item->depth == 1) {
                $fullUrl = '/' . $item->shortUrl;
                $db->createCommand()->update('categories', [
                    'fullUrl' => $fullUrl
                ], ['id' => $item->id])->execute();
            }
            elseif ($item->depth == 2) {
                $parent = Categories::getParent($item);
                $fullUrl = $parent->fullUrl . '/' . $item->shortUrl;
                $db->createCommand()->update('categories', [
                    'fullUrl' => $fullUrl
                ], ['id' => $item->id])->execute();
            }
            elseif ($item->depth == 3) {
                $parent = Categories::getParent($item);
                $fullUrl = $parent->fullUrl . '/' . $item->shortUrl;
                $db->createCommand()->update('categories', [
                    'fullUrl' => $fullUrl
                ], ['id' => $item->id])->execute();
            }
            elseif ($item->depth == 4) {
                $db->createCommand()->update('categories', [
                    'shortUrl' => null,
                    'fullUrl' => null
                ], ['id' => $item->id])->execute();
            }
        }
    }

    public function actionBack() {
        $db = new Query();
        $db->createCommand()->renameColumn('categories', 'shortUrl', 'url')->execute();
        $db->createCommand()->dropColumn('categories', 'fullUrl')->execute();
    }


}