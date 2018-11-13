<?php
/**
 * User Newsletter Histories (user-newsletter-history)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\history\NewsletterController
 * @var $model ommu\users\models\UserNewsletterHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 7 May 2018, 09:00 WIB
 * @modified date 13 November 2018, 23:44 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Newsletter Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->newsletter->email;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="user-newsletter-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'status',
			'value' => $model->status == 1 ? Yii::t('app', 'Subscribe') : Yii::t('app', 'Unsubscribe'),
		],
		[
			'attribute' => 'email_search',
			'value' => isset($model->newsletter) ? $model->newsletter->email : '-',
		],
		[
			'attribute' => 'register_search',
			'value' => $this->filterYesNo($model->newsletter->view->register),
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->newsletter->user) ? $model->newsletter->user->displayname : '-',
		],
		[
			'attribute' => 'level_search',
			'value' => isset($model->newsletter->user->level) ? $model->newsletter->user->level->title->message : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
		],
		'updated_ip',
	],
]) ?>

</div>