<?php

/**
 * @property \Fixture\Database $db
 *
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
class UnitTester extends _AbstractTester
{
    use _generated\UnitTesterActions;

    public function __construct(\Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->db = new \Fixture\Database();
    }
}
