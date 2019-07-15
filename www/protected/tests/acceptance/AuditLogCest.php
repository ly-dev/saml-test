<?php

class AuditLogCest extends _AcceptanceCest
{

    public function iCanViewAnAuditItem(AcceptanceTester $I)
    {
        // generated audit log
        $I->amRegisteredAndLoggedInAsSampleUser();

        // allow difference from login log
        sleep(2);
        $I->login->goToLogoutPage();
        $email = $I->user->email;

        // check the audit log
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $I->auditLog->goToPage();
        $I->dataTable->searchFor($email);

        $I->dataTable->seeFirstItemInListInColumnNumber('Logged out', 4);
        $I->dataTable->seeFirstItemInListInColumnNumber($email, 6);
    }
}
