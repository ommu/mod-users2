<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\InviteController
 * @var $model ommu\users\models\search\UserInviteHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 13 November 2018, 11:54 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-invite-history-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'email_search');?>

		<?php echo $form->field($model, 'displayname_search');?>

		<?php echo $form->field($model, 'inviter_search');?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'userLevel')
			->dropDownList($level, ['prompt' => '']);?>

		<?php echo $form->field($model, 'code');?>

		<?php echo $form->field($model, 'invite_date')
			->input('date');?>

		<?php echo $form->field($model, 'invite_ip');?>

		<?php echo $form->field($model, 'expired_date')
			->input('date');?>

		<?php echo $form->field($model, 'expired_search')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>