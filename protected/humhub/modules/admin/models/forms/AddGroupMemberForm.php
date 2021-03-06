<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\admin\models\forms;

use yii\base\Model;
use yii\web\HttpException;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Group;

/**
 * Description of UserGroupForm
 *
 * @author buddha
 */
class AddGroupMemberForm extends Model
{

    /**
     * GroupId selection array of the form.
     * @var type
     */
    public $userGuids;

    /**
     * User model object
     * @var type
     */
    public $groupId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userGuids', 'groupId'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userGuids' => 'Add Members'
        ];
    }

    /**
     * Sets the user data and intitializes the from selection
     * @param type $user
     */
    public function setUser($group)
    {
        //Set form user and current group settings
        $this->group = $group;
    }

    /**
     * Aligns the given group selection with the db
     * @return boolean
     */
    public function save()
    {
        $group = $this->getGroup();

        if ($group == null) {
            throw new HttpException(404, Yii::t('AdminModule.models_form_AddGroupMemberForm', 'Group not found!'));
        }

        foreach ($this->userGuids as $userGuid) {
            $user = User::findIdentityByAccessToken($userGuid);
            if ($user != null) {
               $group->addUser($user);
            }
        }

        return true;
    }

    public function getGroup()
    {
        return Group::findOne(['id' => $this->groupId]);
    }
}
