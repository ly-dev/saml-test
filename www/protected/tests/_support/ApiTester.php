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
 * Own properties
 * @property \Fixture\Database            $db
 *
 * @property \Api\App		              $app
 * @property \Api\BasicPage               $basicPage
 * @property \Api\Participant                   $participant
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends _AbstractTester
{
    use _generated\ApiTesterActions;

    public function __construct(\Codeception\Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->db = new \Fixture\Database();
        $this->app = new \Api\App($this);
        $this->basicPage = new \Api\BasicPage($this);
        $this->participant = new \Api\Participant($this);
        $this->tracker = new \Api\Tracker($this);
    }
}
