<?php
/**
 * UserForgotQuery
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserForgot]].
 * @see \ommu\users\models\UserForgot
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 May 2018, 13:32 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\users\models\query;

class UserForgotQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserForgot[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserForgot|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
