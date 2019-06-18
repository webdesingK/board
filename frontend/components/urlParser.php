<?


namespace frontend\components;

use frontend\models\Categories;
use frontend\models\Cities;

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