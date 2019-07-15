<?php

/**
 * Base class for API Test
 *
 */
abstract class _ApiCest
{

    protected function getUniqueHash()
    {
        return substr(md5(microtime() . mt_rand()), 0, 8);
    }

    public function _before(ApiTester $I)
    {}

    public function _after(ApiTester $I)
    {}
}