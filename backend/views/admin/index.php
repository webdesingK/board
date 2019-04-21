<?

$this->title = 'Главная';

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\Categories;

?>


<h6>Главная</h6>

<?// $form = ActiveForm::begin() ?>
<!---->
<?//= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(Categories::find()->all(), 'id', 'name')) ?><!--<br>-->
<!---->
<?//= $form->field($model, 'name')->textInput() ?><!--<br>-->
<!---->
<?//= Html::submitButton('добавить') ?>
<!---->
<?// ActiveForm::end() ?>
<!---->
<!---->
<!--    --><?// print_r(Categories::createTree()) ?>

