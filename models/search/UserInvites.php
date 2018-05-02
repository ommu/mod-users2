<?php
/**
 * UserInvites
 * version: 0.0.1
 *
 * UserInvites represents the model behind the search form about `app\modules\user\models\UserInvites`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 08:27 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserInvites as UserInvitesModel;
//use app\modules\user\models\UserNewsletter;
//use app\modules\user\models\Users;

class UserInvites extends UserInvitesModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['invite_id', 'publish', 'newsletter_id', 'user_id', 'invites', 'modified_id'], 'integer'],
			[['code', 'invite_date', 'invite_ip', 'modified_date', 'updated_date', 'newsletter_search', 'user_search', 'modified_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = UserInvitesModel::find()->alias('t');
		$query->joinWith(['newsletter newsletter', 'user user', 'modified modified']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['newsletter_search'] = [
			'asc' => ['newsletter.newsletter_id' => SORT_ASC],
			'desc' => ['newsletter.newsletter_id' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['invite_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.invite_id' => isset($params['id']) ? $params['id'] : $this->invite_id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.newsletter_id' => isset($params['newsletter']) ? $params['newsletter'] : $this->newsletter_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.invites' => $this->invites,
			'cast(t.invite_date as date)' => $this->invite_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.invite_ip', $this->invite_ip])
			->andFilterWhere(['like', 'newsletter.newsletter_id', $this->newsletter_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}