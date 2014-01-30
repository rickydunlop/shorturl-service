<?php

namespace Sonrisa\Service\ShortLink\Tests;

class GoogleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var object
     */
    private $provider;

    protected function tearDown()
    {
        unset($this->provider);
    }

    /**
     * Creates a mock object that does not hit Goog.gl by replacing the "request" method ONLY.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBaseMockResponse()
    {
        return $this->getMock('Sonrisa\Service\ShortLink\Google',array('request'));
    }

    /**
     *
     */
    public function testShortenWithValidApiResponse()
    {
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/"
}
JSON;

        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));

        $this->assertEquals('http://goo.gl/fbsS',$this->provider->shorten('http://www.google.com/'));
    }

    /**
     *
     */
    public function testShortenThrowsExceptionInvalidURL1()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.invalid.domain/');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionInvalidURL2()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('./hello/world');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionInvalidURL404()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/i-dont-happen-to-exist.html');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionIfResponseApiIsString()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
Some random string
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionIfApiResponseIsError()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "error": {
  "errors": [
   {
    "domain": "global",
    "reason": "required",
    "message": "Required",
    "locationType": "parameter",
    "location": "resource.longUrl"
   }
  ],
  "code": 400,
  "message": "Required"
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionIfApiResponseHasNoId()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "longUrl": "http://www.google.com/"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }

    /**
     *
     */
    public function testShortenThrowsExceptionIfApiResponseHasNoLongUrl()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }








    /**
     *
     */
    public function testExpandWithValidApiResponse()
    {
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));

        $this->assertEquals("http://www.google.com/",$this->provider->expand("http://goo.gl/fbsS"));
    }


    /**
     *
     */
    public function testExpandThrowsExceptionInvalidURL1()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('http://www.invalid.domain/');
    }

    /**
     *
     */
    public function testExpandThrowsExceptionInvalidURL2()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('./hello/world');
    }

    /**
     *
     */
    public function testExpandThrowsExceptionInvalidURL404()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('http://www.google.com/i-dont-happen-to-exist.html');
    }

    /**
     *
     */
    public function testExpandThrowsExceptionIfResponseApiIsString()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
Some random string
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseIsError()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "error": {
  "errors": [
   {
    "domain": "global",
    "reason": "required",
    "message": "Required",
    "locationType": "parameter",
    "location": "resource.longUrl"
   }
  ],
  "code": 400,
  "message": "Required"
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseHasNoId()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "longUrl": "http://www.google.com/",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseHasNoLongUrl()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "status": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseHasNoStatus()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/"
}
JSON;

        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }


    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseHasInvalidStatusCode()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "KO"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand("http://goo.gl/fbsS");
    }














    /**
     *
     */
    public function testStatsWithValidApiResponse()
    {
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" } ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));

        $this->assertTrue(is_array($this->provider->stats("http://goo.gl/fbsS")));
    }

    /**
     *
     */
    public function testStatsThrowsExceptionInvalidURL1()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" } ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats('http://www.invalid.domain/');
    }

    /**
     *
     */
    public function testStatsThrowsExceptionInvalidURL2()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" } ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats('./hello/world');
    }

    /**
     *
     */
    public function testStatsThrowsExceptionInvalidURL404()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" } ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats('http://www.google.com/i-dont-happen-to-exist.html');
    }


    /**
     *
     */
    public function testStatsThrowsExceptionIfResponseApiIsString()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
Some random string
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testStatsThrowsExceptionIfApiResponseIsError()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "error": {
  "errors": [
   {
    "domain": "global",
    "reason": "required",
    "message": "Required",
    "locationType": "parameter",
    "location": "resource.longUrl"
   }
  ],
  "code": 400,
  "message": "Required"
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testStatsThrowsExceptionIfApiResponseHasNoId()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "longUrl": "http://www.google.com/",
 "status": "KO",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" }  ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testStatsThrowsExceptionIfApiResponseHasNoLongUrl()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "status": "OK",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" }  ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }

    /**
     *
     */
    public function testStatsThrowsExceptionIfApiResponseHasNoStatus()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" }  ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;

        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }


    /**
     *
     */
    public function testStatsThrowsExceptionIfApiResponseHasInvalidStatusCode()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
 "kind": "urlshortener#url",
 "id": "http://goo.gl/fbsS",
 "longUrl": "http://www.google.com/",
 "status": "KO",
 "created": "2009-12-13T07:22:55.000+00:00",
 "analytics": {
  "allTime": {
   "shortUrlClicks": "3227",
   "longUrlClicks": "9358",
   "referrers": [ { "count": "2160", "id": "Unknown/empty" }  ],
   "countries": [ { "count": "1022", "id": "US" }  ],
   "browsers": [ { "count": "1025", "id": "Firefox" }  ],
   "platforms": [ { "count": "2278", "id": "Windows" }  ]
  }
 }
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->stats("http://goo.gl/fbsS");
    }

}
