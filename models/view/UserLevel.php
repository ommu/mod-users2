<?php
/**
 * UserLevel
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 October 2017, 14:41 WIB
 * @modified date 2 May 2018, 13:16 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_user_level".
 *
 * The followings are the available columns in table "_user_level":
 * @property integer $level_id
 * @property string $user_active
 * @property string $user_pending
 * @property string $user_noverified
 * @property string $user_blocked
 * @property integer $user_all
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserLevel extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_level';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['level_id'];
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['level_id', 'user_all'], 'integer'],
			[['user_active', 'user_pending', 'user_noverified', 'user_blocked'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'level_id' => Yii::t('app', 'Level'),
			'user_active' => Yii::t('app', 'User Active'),
			'user_pending' => Yii::t('app', 'User Pending'),
			'user_noverified' => Yii::t('app', 'User Noverified'),
			'user_blocked' => Yii::t('app', 'User Blocked'),
			'user_all' => Yii::t('app', 'User All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['level_id'] = [
			'attribute' => 'level_id',
			'value' => function($model, $key, $index, $column) {
				return $model->level_id;
			},
		];
		$this->templateColumns['user_active'] = [
			'attribute' => 'user_active',
			'value' => function($model, $key, $index, $column) {
				return $model->user_active;
			},
		];
		$this->templateColumns['user_pending'] = [
			'attribute' => 'user_pending',
			'value' => function($model, $key, $index, $column) {
				return $model->user_pending;
			},
		];
		$this->templateColumns['user_noverified'] = [
			'attribute' => 'user_noverified',
			'value' => function($model, $key, $index, $column) {
				return $model->user_noverified;
			},
		];
		$this->templateColumns['user_blocked'] = [
			'attribute' => 'user_blocked',
			'value' => function($model, $key, $index, $column) {
				return $model->user_blocked;
			},
		];
		$this->templateColumns['user_all'] = [
			'attribute' => 'user_all',
			'value' => function($model, $key, $index, $column) {
				return $model->user_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['level_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
