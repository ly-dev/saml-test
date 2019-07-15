<?php

class TrackerCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();
    }

    public function iCanAccessThePage(FunctionalTester $I)
    {
        $I->tracker->goToPage();
    }

    public function iCanManipulateData(FunctionalTester $I)
    {
        $participantA = $I->db->participant->create();
        $participantB = $I->db->participant->create();

        $obj1 = $I->db->tracker->make(['participant_id' => $participantA->id]);
        $obj1->date = '2018-01-01 10:00:00';
        $I->db->tracker->create($obj1);
        $obj2 = $I->db->tracker->make(['participant_id' => $participantA->id]);
        $obj2->date = '2018-01-01 00:00:00';
        $I->db->tracker->create($obj2);
        $obj3 = $I->db->tracker->make(['participant_id' => $participantA->id]);
        $obj3->date = '2018-01-01 20:00:00';
        $I->db->tracker->create($obj3);

        $I->db->tracker->create(['participant_id' => $participantB->id]);
        $I->db->tracker->create(['participant_id' => $participantB->id]);
        $I->db->tracker->create(['participant_id' => $participantB->id]);

        /// START

        $baseQuery = [
            'draw' => 1,
            'start' => 0,
            'length' => 999,
            'order' => [
                [
                    'column' => 0, 
                    'dir' => 'asc'
                ]
            ],
            'search' => [
                'value' => null,
                'regex' => false
            ]
        ];
        
        // Basic
        $q = $baseQuery;
        $I->amOnPage("/tracker/grid?" . http_build_query($q));
        $I->seeInSource('"draw":"1"');

        // Filter
        $q = $baseQuery;
        $q['search']['value'] = $participantA->device_uuid;
        $I->amOnPage("/tracker/grid?" . http_build_query($q));
        $I->seeInSource('"recordsTotal":3');
        $I->seeInSource('"device_uuid":"' . $participantA->device_uuid . '"');
        $I->dontSeeInSource('"device_uuid":"' . $participantB->device_uuid . '"');

        // Order (by ID)
        $q = $baseQuery;
        $q['search']['value'] = $participantA->device_uuid;
        $I->amOnPage("/tracker/grid?" . http_build_query($q));
        $data = json_decode($I->grabPageSource());
        $I->assertEquals($obj1->date, $data->data[0]->date);
        $I->assertEquals($obj3->date, $data->data[2]->date);

        // Order (by Date) - ASC
        $q = $baseQuery;
        $q['search']['value'] = $participantA->device_uuid;
        $q['order'][0]['column'] = 1;
        $I->amOnPage("/tracker/grid?" . http_build_query($q));
        $data = json_decode($I->grabPageSource());
        $I->assertEquals($obj2->date, $data->data[0]->date);
        $I->assertEquals($obj3->date, $data->data[2]->date);

        // Order (by Date) - DESC
        $q = $baseQuery;
        $q['search']['value'] = $participantA->device_uuid;
        $q['order'][0]['column'] = 1;
        $q['order'][0]['dir'] = 'desc';
        $I->amOnPage("/tracker/grid?" . http_build_query($q));
        $data = json_decode($I->grabPageSource());
        $I->assertEquals($obj3->date, $data->data[0]->date);
        $I->assertEquals($obj2->date, $data->data[2]->date);
    }
}
