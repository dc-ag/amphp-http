<?php

namespace Amp\Http\Cookie;

use PHPUnit\Framework\TestCase;

class ResponseCookieTest extends TestCase {
    public function testParsing() {
        // Examples from https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie
        $this->assertEquals(
            new ResponseCookie("sessionid", "38afes7a8", CookieAttributes::empty()->withHttpOnly()->withPath("/")),
            ResponseCookie::fromHeader("sessionid=38afes7a8; HttpOnly; Path=/")
        );

        $expectedMeta = CookieAttributes::empty()
            ->withHttpOnly()
            ->withSecure()
            ->withExpiry(new \DateTimeImmutable("Wed, 21 Oct 2015 07:28:00", new \DateTimeZone("GMT")));

        $this->assertEquals(
            new ResponseCookie("id", "a3fWa", $expectedMeta),
            ResponseCookie::fromHeader("id=a3fWa; Expires=Wed, 21 Oct 2015 07:28:00 GMT; Secure; HttpOnly")
        );

        $expectedMeta = CookieAttributes::empty()
            ->withDomain("example.com")
            ->withPath("/")
            ->withExpiry(new \DateTimeImmutable("Wed, 30 Aug 2019 00:00:00", new \DateTimeZone("GMT")));

        $this->assertEquals(
            new ResponseCookie("qwerty", "219ffwef9w0f", $expectedMeta),
            $cookie = ResponseCookie::fromHeader("qwerty=219ffwef9w0f; Domain=example.com; Path=/; Expires=Wed, 30 Aug 2019 00:00:00 GMT")
        );

        $this->assertFalse($cookie->isSecure());
        $this->assertFalse($cookie->isHttpOnly());
        $this->assertSame("qwerty", $cookie->getName());
        $this->assertSame("219ffwef9w0f", $cookie->getValue());
        $this->assertSame("example.com", $cookie->getDomain());
        $this->assertSame("/", $cookie->getPath());
        $this->assertSame(
            (new \DateTimeImmutable("Wed, 30 Aug 2019 00:00:00", new \DateTimeZone("GMT")))->getTimestamp(),
            $cookie->getExpires()
        );

        $this->assertNull(
            ResponseCookie::fromHeader("query foo=129")
        );
    }
}
