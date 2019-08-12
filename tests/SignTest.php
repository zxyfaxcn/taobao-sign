<?php

namespace Assert6Test\Mtop;

use Assert6\Mtop\Signer;
use PHPUnit\Framework\TestCase;

class SignTest extends TestCase
{
    protected $token = 'a31fb3e0132de243d1d5023542d7ff29';
    protected $appKey = '12574478';

    public function testSign()
    {
        $signer = new Signer();
        $data = $this->token . "&1565160026527&" . $this->appKey . '&{"mailNo":"7700130348198","cpCode":""}';
        $this->assertEquals('029ca989c1e2bb08a3f027d325bfa225', $signer->sign($data));
    }
}