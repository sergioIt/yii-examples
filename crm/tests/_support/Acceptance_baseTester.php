<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class Acceptance_baseTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param string $login
     */
   public function loggedAs(string $login){

       $I = $this;

       $I->amOnPage('/auth/login');
       $I->see('Please fill out the following fields to login:');
       $I->fillField('#loginformsupport-login', $login);
       $I->fillField('#loginformsupport-password', '12345');
       $I->click('button[name=login-button]');
       $I->dontSee('button[name=login-button]');
   }

    /**
     *
     */
   public function seeForbidden(){
       $I = $this;

       $I->see('Forbidden');
   }
}
