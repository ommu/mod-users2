<?php
/**
 * User Invites (user-invites)
 * @var $this InviteController
 * @var $model UserInvites
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 5 August 2017, 17:43 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Invites'=>array('manage'),
		$model->invite_id=>array('view','id'=>$model->invite_id),
		'View',
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'invite_id',
			array(
				'name'=>'publish',
				'value'=>$model->publish == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			array(
				'name'=>'email_i',
				'value'=>$model->newsletter->email,
			),
			array(
				'name'=>'user_search',
				'value'=>$model->user_id ? $model->user->displayname : '-',
			),
			array(
				'name'=>'userlevel_search',
				'value'=>$model->user_id ? $model->user->level->title->message : '-',
			),
			array(
				'name'=>'code',
				'value'=>$model->code,
			),
			array(
				'name'=>'invites',
				'value'=>$model->invites,
			),
			array(
				'name'=>'invite_date',
				'value'=>!in_array($model->invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->invite_date, true) : '-',
			),
			array(
				'name'=>'invite_ip',
				'value'=>$model->invite_ip,
			),
			array(
				'name'=>'register_search',
				'value'=>$model->newsletter->view->register ? $this->renderPartial('_view_register', array('model'=>$model), true, false) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>