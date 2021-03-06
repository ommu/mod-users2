<?php
/**
 * UserInvites
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 17 October 2017, 14:18 WIB
 * @modified date 13 November 2018, 11:30 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_invites".
 *
 * The followings are the available columns in table "ommu_user_invites":
 * @property integer $id
 * @property integer $publish
 * @property integer $newsletter_id
 * @property integer $level_id
 * @property string $displayname
 * @property string $code
 * @property integer $invites
 * @property integer $inviter_id
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property UserInviteHistory[] $histories
 * @property UserNewsletter $newsletter
 * @property Users $inviter
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\CoreSettings;

class UserInvites extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['level_id', 'code', 'invite_ip', 'modified_date', 'modifiedDisplayname', 'updated_date', 'userLevel'];
	public $email_i;
	public $old_invites_i;

	public $inviter_search;
	public $modifiedDisplayname;
	public $email_search;
	public $userLevel;

	const SCENARIO_FORM = 'createForm';
	const SCENARIO_SINGLE_EMAIL = 'singleEmail';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_invites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['level_id', 'email_i'], 'required'],
			// [['email_i'], 'required', 'on' => self::SCENARIO_FORM],
			// [['email_i'], 'required', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['publish', 'newsletter_id', 'level_id', 'invites', 'inviter_id', 'modified_id', 'old_invites_i'], 'integer'],
			[['email_i'], 'string'],
			[['email_i'], 'email', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['email_i'], 'safe'],
			[['displayname'], 'string', 'max' => 64],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
			[['inviter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['inviter_id' => 'user_id']],
			[['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_FORM] = ['level_id', 'email_i'];
		$scenarios[self::SCENARIO_SINGLE_EMAIL] = ['level_id', 'email_i'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'level_id' => Yii::t('app', 'Level'),
			'displayname' => Yii::t('app', 'Displayname'),
			'code' => Yii::t('app', 'Code'),
			'invites' => Yii::t('app', 'Invites'),
			'inviter_id' => Yii::t('app', 'Inviter'),
			'invite_date' => Yii::t('app', 'Invite Date'),
			'invite_ip' => Yii::t('app', 'Invite IP'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'email_i' => Yii::t('app', 'Email'),
			'inviter_search' => Yii::t('app', 'Inviter'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'email_search' => Yii::t('app', 'Email'),
			'userLevel' => Yii::t('app', 'Level'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(UserInviteHistory::className(), ['invite_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletter()
	{
		return $this->hasOne(UserNewsletter::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInviter()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'inviter_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel()
	{
		return $this->hasOne(UserLevel::className(), ['level_id' => 'level_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserInvites the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserInvites(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname ? $model->displayname : '-';
			},
		];
		$this->templateColumns['email_search'] = [
			'attribute' => 'email_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->newsletter) ? Yii::$app->formatter->asEmail($model->newsletter->email) : '-';
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('newsletter') ? true : false,
		];
		$this->templateColumns['level_id'] = [
			'attribute' => 'level_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->level) ? $model->level->name_i : '-';
			},
			'filter' => UserLevel::getLevel(),
			'visible' => !Yii::$app->request->get('level') ? true : false,
		];
		$this->templateColumns['inviter_search'] = [
			'attribute' => 'inviter_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->inviter) ? $model->inviter->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('inviter') ? true : false,
		];
		$this->templateColumns['userLevel'] = [
			'attribute' => 'userLevel',
			'value' => function($model, $key, $index, $column) {
				return isset($model->inviter->level) ? $model->inviter->level->title->message : '-';
			},
			'filter' => UserLevel::getLevel(),
			'visible' => !Yii::$app->request->get('inviter') ? true : false,
		];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code ? $model->code : '-';
			},
		];
		$this->templateColumns['invite_date'] = [
			'attribute' => 'invite_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->invite_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'invite_date'),
		];
		$this->templateColumns['invite_ip'] = [
			'attribute' => 'invite_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_ip;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['invites'] = [
			'attribute' => 'invites',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->invites, ['history/invite/index', 'invite' => $model->primaryKey]);
			},
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * insertInvite
	 * 
	 * condition
	 * 0 = invite not null
	 * 1 = invite save
	 * 2 = invite not save
	 */
	public static function insertInvite($email, $displayname=null, $inviter_id=null)
	{
		$email = strtolower($email);
        if ($inviter_id === null) {
            $inviter_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        }

		$invite = self::find()->alias('t')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 't.newsletter_id=newsletter.newsletter_id')
			->select(['t.id', 't.newsletter_id', 't.invites'])
			->where(['t.publish' => 1])
			->andWhere(['t.inviter_id' => $inviter_id])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.id DESC')
			->one();

		$condition = 0;
        if ($invite == null) {
			$invite = new UserInvites();
			$invite->scenario = self::SCENARIO_SINGLE_EMAIL;
			$invite->email_i = $email;
            if ($displayname !== null && $email != $displayname) {
                $invite->displayname = $displayname;
            }
			$invite->inviter_id = $inviter_id;
            if ($invite->save()) {
                $condition = 1;
            } else {
                $condition = 2;
            }

		} else {
            if ($invite->newsletter->user_id == null) {
                if ($displayname !== null && $email != $displayname)
					$invite->displayname = $displayname;
				$invite->invites = $invite->invites+1;
                if ($invite->save()) {
                    $condition = 1;
                } else {
                    $condition = 2;
                }
            }
        }

		return $condition;
	}

	// getInvite
	public static function getInvite($email)
	{
		$email = strtolower($email);
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 't.newsletter_id=newsletter.newsletter_id')
			->select(['t.id', 't.newsletter_id'])
			->where(['t.publish' => 1])
			->andWhere(['is not', 't.inviter_id', null])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.id DESC')
			->one();
		
		return $model;
	}

	// getInviteWithCode
	public static function getInviteWithCode($email, $code)
	{
		$email = strtolower($email);
		$model = UserInviteHistory::find()->alias('t')
			->leftJoin(sprintf('%s invite', UserInvites::tableName()), 't.invite_id=invite.id')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 'invite.newsletter_id=newsletter.newsletter_id')
			->select(['t.id', 't.invite_id'])
			->where(['t.code' => $code])
			->andWhere(['invite.publish' => 1])
			// ->andWhere(['newsletter.status' => 1])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.id DESC')
			->one();
		
		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_invites_i = $this->invites;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
				$this->email_i = strtolower($this->email_i);
                if ($this->email_i != '') {
					$email_i = $this->formatFileType($this->email_i);
                    if (count($email_i) == 1) {
						$newsletter = UserNewsletter::find()
							->select(['newsletter_id', 'user_id'])
							->where(['email' => $this->email_i])
							->one();
                        if ($newsletter != null && $newsletter->user_id != null) {
                            $this->addError('email_i', Yii::t('app', 'Email {email} sudah terdaftar sebagai member.', ['email' => $this->email_i]));
                        }
					}
				}
                if ($this->inviter_id == null) {
                    $this->inviter_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }

                if (Yii::$app->isSocialMedia()) {
                    $this->level_id = UserLevel::getDefault();
                }

            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }

            if ($this->isNewRecord || (!$this->isNewRecord && $this->old_invites_i != $this->invites)) {
                $this->code = Yii::$app->security->generateRandomString(16);
            }

			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
			$this->email_i = strtolower($this->email_i);
			
            if ($insert) {
				$newsletter = UserNewsletter::find()
					->select(['newsletter_id'])
					->where(['email' => $this->email_i])
					->one();

                if ($newsletter != null) {
                    $this->newsletter_id = $newsletter->newsletter_id;
                } else {
					$newsletter = new UserNewsletter();
					$newsletter->status = 0;
					$newsletter->email_i = $this->email_i;
                    if ($newsletter->save()) {
                        $this->newsletter_id = $newsletter->newsletter_id;
                    }
				}

            }
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

		$setting = CoreSettings::find()
			->select(['signup_checkemail'])
			->where(['id' => 1])
			->one();
		
        if ($this->newsletter->user_id == null) {
			$displayname = $this->displayname ? $this->displayname : $this->newsletter->email;
			$inviter = $this->inviter->displayname ? $this->inviter->displayname : $this->inviter->email;
			$singuplink = $setting->signup_checkemail == 1 ? Url::to(['signup/index', 'code' => $this->code], true) : Url::to(['signup/index'], true);
			
			// if ($insert) {
			// 	$template = $setting->signup_checkemail == 1 ? 'invite-code' : 'invite';
			// 	$emailSubject = $this->parseMailSubject($template, 'user');
			// 	$emailBody = $this->parseMailBody($template, [
			// 		'displayname' => $displayname, 
			// 		'inviter' => $inviter, 
			// 		'singup-link' => $singuplink, 
			// 		'invite-code' => $this->code,
			// 	], 'user');

			// 	Yii::$app->mailer->compose()
			// 		->setFrom($this->getMailFrom())
			// 		->setTo([$this->newsletter->email => $displayname])
			// 		->setSubject($emailSubject)
			// 		->setHtmlBody($emailBody)
			// 		->send();

			// } else {
			//     if ($this->old_invites_i != $this->invites) {
			// 		$template = $setting->signup_checkemail == 1 ? 'invite-2nd-code' : 'invite-2nd';
			// 		$emailSubject = $this->parseMailSubject($template, 'user');
			// 		$emailBody = $this->parseMailBody($template, [
			// 			'displayname' => $displayname, 
			// 			'invites' => $this->invites, 
			// 			'inviter' => $inviter, 
			// 			'singup-link' => $singuplink, 
			// 			'invite-code' => $this->code,
			// 		], 'user');
	
			// 		Yii::$app->mailer->compose()
			// 			->setFrom($this->getMailFrom())
			// 			->setTo([$this->newsletter->email => $displayname])
			// 			->setSubject($emailSubject)
			// 			->setHtmlBody($emailBody)
			// 			->send();
			// 	}
			// }
		}
	}
}
