<?php
/**
 * User Forgots (user-forgot)
 * @var $this yii\web\View
 * @var $this app\modules\user\controllers\ForgotController
 * @var $model app\modules\user\models\search\UserForgot
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 17 October 2017, 15:01 WIB
 * @modified date 3 May 2018, 14:11 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'publish')
			->checkbox();?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'code');?>

		<?php echo $form->field($model, 'forgot_date')
			->input('date');?>

		<?php echo $form->field($model, 'forgot_ip');?>

		<?php echo $form->field($model, 'expired_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'deleted_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
