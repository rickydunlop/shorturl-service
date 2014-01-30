<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Service\ShortLink\Tests;

use Sonrisa\Service\ShortLink\Bitly;

class BitlyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var object
     */
    protected $provider;

    /**
     * Cleanups the provider
     */
    public function tearDown()
    {
        unset($this->provider);
    }

    /**
     * Gets mock of response
     *
     * @return object
     */
    private function getBaseMockResponse()
    {
        return $this->getMockBuilder('\Sonrisa\Service\ShortLink\Bitly')
            ->disableOriginalConstructor()
            ->setMethods(array('request'))
            ->getMock();

    }

    /**
     *
     */
    public function testConstructorThrowsExceptionIfCredentialsInvalid()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $bitly = new \Sonrisa\Service\ShortLink\Bitly('fakeuser','fakepassword');
        $bitly->shorten('http://google.com');
    }

    /**
     *
     */
    public function testShortenWithValidApiResponse()
    {
        $apiRawResponse = <<<JSON
{
  "data": {
    "global_hash": "900913",
    "hash": "ze6poY",
    "long_url": "http://google.com/",
    "new_hash": 0,
    "url": "http://bit.ly/ze6poY"
  },
  "status_code": 200,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));


        $this->assertEquals('http://bit.ly/ze6poY',$this->provider->shorten('http://google.com/'));
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
        $this->provider->shorten('http://google.com/');
    }


    /**
     *
     */
    public function testShortenThrowsExceptionIfApiResponseHasNoData()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "status_code": 500
  "status_txt": "KO"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }



    /**
     *
     */
    public function testShortenThrowsExceptionIfApiResponseIsNotCode200()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "data": {
    "global_hash": "900913",
    "hash": "ze6poY",
    "long_url": "http://google.com/",
    "new_hash": 0,
    "url": "http://bit.ly/ze6poY"
  },
  "status_code": 500
  "status_txt": "KO"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/');
    }



    /**
     *
     */
    public function testShortenThrowsExceptionInvalidURL1()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "data": {
    "global_hash": "900913",
    "hash": "ze6poY",
    "long_url": "http://google.com/",
    "new_hash": 0,
    "url": "http://bit.ly/ze6poY"
  },
  "status_code": 200,
  "status_txt": "OK"
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
  "data": {
    "global_hash": "900913",
    "hash": "ze6poY",
    "long_url": "http://google.com/",
    "new_hash": 0,
    "url": "http://bit.ly/ze6poY"
  },
  "status_code": 200,
  "status_txt": "OK"
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
  "data": {
    "global_hash": "900913",
    "hash": "ze6poY",
    "long_url": "http://google.com/",
    "new_hash": 0,
    "url": "http://bit.ly/ze6poY"
  },
  "status_code": 200,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->shorten('http://www.google.com/i-dont-happen-to-exist.html');
    }











    /**
     *
     */
    public function testExpandWithValidApiResponse()
    {
        $apiRawResponse = <<<JSON
{
  "data": {
    "expand": [
      {
        "global_hash": "900913",
        "long_url": "http://google.com/",
        "short_url": "http://bit.ly/ze6poY",
        "user_hash": "ze6poY"
      }
    ]
  },
  "status_code": 200,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->once())->method('request')->will($this->returnValue($apiRawResponse));


        $this->assertEquals('http://google.com/',$this->provider->expand('http://bit.ly/ze6poY'));
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
        $this->provider->expand('http://bit.ly/ze6poY');
    }


    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseHasNoData()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "status_code": 200,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('http://www.google.com/');
    }



    /**
     *
     */
    public function testExpandThrowsExceptionIfApiResponseIsNotCode200()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "data": {
    "expand": [
      {
        "global_hash": "900913",
        "long_url": "http://google.com/",
        "short_url": "http://bit.ly/ze6poY",
        "user_hash": "ze6poY"
      }
    ]
  },
  "status_code": 500,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('http://www.google.com/');
    }



    /**
     *
     */
    public function testExpandThrowsExceptionInvalidURL1()
    {
        $this->setExpectedException('Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException');
        $apiRawResponse = <<<JSON
{
  "data": {
    "expand": [
      {
        "global_hash": "900913",
        "long_url": "http://google.com/",
        "short_url": "http://bit.ly/ze6poY",
        "user_hash": "ze6poY"
      }
    ]
  },
  "status_code": 200,
  "status_txt": "OK"
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
  "data": {
    "expand": [
      {
        "global_hash": "900913",
        "long_url": "http://google.com/",
        "short_url": "http://bit.ly/ze6poY",
        "user_hash": "ze6poY"
      }
    ]
  },
  "status_code": 200,
  "status_txt": "OK"
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
  "data": {
    "expand": [
      {
        "global_hash": "900913",
        "long_url": "http://google.com/",
        "short_url": "http://bit.ly/ze6poY",
        "user_hash": "ze6poY"
      }
    ]
  },
  "status_code": 200,
  "status_txt": "OK"
}
JSON;
        $this->provider = $this->getBaseMockResponse();
        $this->provider->expects($this->any())->method('request')->will($this->returnValue($apiRawResponse));
        $this->provider->expand('http://www.google.com/i-dont-happen-to-exist.html');
    }

}
