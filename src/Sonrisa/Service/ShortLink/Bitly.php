<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Service\ShortLink;

use Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException;
use Sonrisa\Service\ShortLink\Interfaces\ShortLinkAbstractInterface;

class Bitly extends ShortLinkAbstractInterface
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * Bit.ly's API Urls
     *
     * @var array
     */
    private $api = array
    (
        'oauth'         => 'https://api-ssl.bitly.com/oauth/access_token',
        'shorten'       => 'https://api-ssl.bitly.com/v3/shorten?access_token=%s&longUrl=%s&domain=%s',
        'expand'        => 'https://api-ssl.bitly.com/v3/expand?access_token=%s&shortUrl=%s&hash=%s',
    );


    /**
     * @param $username
     * @param $password
     */
    public function __construct($username,$password)
    {
        $this->accessToken = $this->getAccessToken($username,$password);
    }

    /**
     * @param  string                                                            $longUrl
     * @param  string                                                            $domain  The domain to use, optional (bit.ly | j.mp | bitly.com)
     * @return mixed
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    public function shorten($longUrl, $domain = 'bit.ly')
    {
        if ($this->urlCheck($longUrl)) {
            $url = sprintf($this->api['shorten'], $this->accessToken,urlencode($longUrl),$domain);
            $data = $this->request($url,stream_context_create( array('http' => array('ignore_errors' => true) ) ) );
            $data = json_decode($data,true);
            $data = $this->validate($data);

            if (empty($data['data']['url'])) {
                throw new InvalidApiResponseException('Bit.ly returned a response that could not be handled');
            } else {
                return $data['data']['url'];
            }
        }
        throw new InvalidApiResponseException('The URL provided for shortening is not valid or currently unavailable.');
    }

    /**
     * @param  string                                                            $shortUrl
     * @return string
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    public function expand($shortUrl)
    {
        if ($this->urlCheck($shortUrl)) {
            $parts = explode('/',$shortUrl);
            $hash = array_pop($parts);

            $url = sprintf($this->api['expand'],$this->accessToken,urlencode($shortUrl), $hash);
            $data = $this->request($url,stream_context_create( array('http' => array('ignore_errors' => true) ) ) );
            $data = json_decode($data,true);
            $data = $this->validate($data);

            if (empty($data['data']['expand']['0']['long_url'])) {
                throw new InvalidApiResponseException('Bit.ly returned a response that could not be handled');
            } else {
                return $data['data']['expand']['0']['long_url'];
            }

        }
        throw new InvalidApiResponseException('The URL provided for expansion is not valid or currently unavailable.');
    }


    /**
     * Sends a POST request to retrieve the Access Token
     *
     * @param $username
     * @param $password
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     * @return string
     */
    protected function getAccessToken($username,$password)
    {
        $request = array
        (
            'http'=> array
            (
                'method'        =>  "POST",
                'header'        =>  "Authorization: Basic ".base64_encode("$username:$password")."\r\n".
                                    "Content-Type: application/x-www-form-urlencoded\r\n",
                'ignore_errors' => true
            )
        );

        $data = file_get_contents($this->api['oauth'], false, stream_context_create($request));

        if ($data == 'INVALID_LOGIN') {
            throw new InvalidApiResponseException('Credentials for Bit.ly are invalid.');
        } else {
            return $data;
        }
    }

    /**
     * Validates the Bit.ly's response and returns it when the status code is 200
     *
     * @param  array                                                             $data
     * @return array
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    protected function validate($data)
    {
        if ( is_array($data) === false || empty($data['data']) || (empty($data['status_code']) && empty($data['status_txt'])) ) {
            throw new InvalidApiResponseException('Bit.ly returned a response that could not be handled');
        }

        if (!empty($data['status_code']) && (200 != $data['status_code']) ) {
            throw new InvalidApiResponseException
            (sprintf
                (
                    'Bit.ly returned an error message. "%s": "%s"',
                    (!empty($data['status_code']))? $data['status_code'] : '<no code provided>',
                    (!empty($data['status_txt']))? $data['status_txt'] : '<no status code provided>'
            ));
        }

        return $data;
    }
}
