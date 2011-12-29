<?php

class m111229_152000_user_previous_visit extends CDbMigration
  {
    public function safeUp()
    {
        $this->addColumn(Yii::app()->getModule('user')->tableUsers, 'previousvisit_at', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER lastvisit_at");
        $this->execute('
            UPDATE ' . Yii::app()->getModule('user')->tableUsers . '
            SET `previousvisit_at` = `lastvisit_at`
        ');
    }
    public function saveDown()
    {
        $this->dropColumn(Yii::app()->getModule('user')->tableUsers, 'previousvisit');
    }
}