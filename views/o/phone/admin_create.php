<?php
/**
 * User Phones (user-phones)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\PhoneController
 * @var $model ommu\users\models\UserPhones
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 14 November 2018, 15:16 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="user-phones-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
