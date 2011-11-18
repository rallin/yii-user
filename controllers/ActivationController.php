<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("You account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
                if (!Yii::app()->controller->module->autoLogin) {
                    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("You account is activated.")));
                } else {
                    $identity=new UserIdentity($find->username, '');
                    $identity->authenticate(true);
                    Yii::app()->user->login($identity,0);
                    Yii::app()->user->setFlash('userActivationSuccess', UserModule::t("You account is activated."));
                    $this->redirect(Yii::app()->controller->module->returnUrl);
                }
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
		}
	}

}