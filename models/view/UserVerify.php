<?php
/**
 * UserVerify
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 October 2017, 08:14 WIB
 * @modified date 2 May 2018, 13:17 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_verify".
 *
 * The followings are the available columns in table "_user_verify":
 * @property integer $verify_id
 * @property integer $expired
 * @property integer $verify_day_left
 * @property integer $verify_hour_left
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserVerify extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_verify';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['verify_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['verify_id', 'expired', 'verify_day_left', 'verify_hour_left'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'verify_id' => Yii::t('app', 'Verify'),
			'expired' => Yii::t('app', 'Expired'),
			'verify_day_left' => Yii::t('app', 'Verify Day Left'),
			'verify_hour_left' => Yii::t('app', 'Verify Hour Left'),
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
		$this->templateColumns['verify_id'] = [
			'attribute' => 'verify_id',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_id;
			},
		];
		$this->templateColumns['expired'] = [
			'attribute' => 'expired',
			'value' => function($model, $key, $index, $column) {
				return $model->expired;
			},
		];
		$this->templateColumns['verify_day_left'] = [
			'attribute' => 'verify_day_left',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_day_left;
			},
		];
		$this->templateColumns['verify_hour_left'] = [
			'attribute' => 'verify_hour_left',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_hour_left;
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
				->where(['verify_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
