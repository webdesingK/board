<?


namespace frontend\components;

use yii\db\Query;
use frontend\models\Categories;
use frontend\models\Cities;
use yii\helpers\ArrayHelper;

class urlParser {

    static public function getArray(array $get) {

        $array = null;
        $categories = Categories::getAllData();
        $cities = Cities::getAllData();

        if (!empty($get)) {

            if (isset($get['city'])) {

                if ($get['city'] == 'Все-города') {
                    $array['city'] = [
                        'name' => 'Все города',
                        'url' => 'Все-города'
                    ];
                }
                else {
                    foreach ($cities as $city) {
                        if ($city['url'] == $get['city']) {
                            $array['city'] = $city;
                            break;
                        }
                        else {
                            $array['city'] = 'error';
                        }
                    }
                }
            }

            if (isset($get['category'])) {
                foreach ($categories as $category) {
                    if ($category['url'] == $get['category']) {
                        $array['category'] = $category;
                        break;
                    }
                    else {
                        $array['category'] = 'error';
                    }
                }
            }

            if (isset($get['filters'])) {
                $filters = explode(';', $get['filters']);
                $newFilters = [];
                foreach ($filters as $key => $value) {

                    if (empty($value)) continue;

                    $filter = explode('=', $value);

                    if ($filter[0] == 'цена') {
                        $priceValues = explode('-', $filter[1]);
                        $newFilters['price'] = [
                            'min' => $priceValues[0],
                            'max' => $priceValues[1]
                        ];
                    }

                    if ($filter[0] == 'тип') {
                        $typeFilter = explode(',', $filter[1]);
                        if (count($typeFilter) > 1) {
                            $newFilters['type'] = $typeFilter;
                        }
                        else {
                            $newFilters['type'] = $typeFilter[0];
                        }
                    }

                }

                $array['filters'] = $newFilters;
            }

        }
        else {
            $array['city'] = [
                'name' => 'Все города',
                'url' => 'Все-города'
            ];
        }
        return $array;

    }

}