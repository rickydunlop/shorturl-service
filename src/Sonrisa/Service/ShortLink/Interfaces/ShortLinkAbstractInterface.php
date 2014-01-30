<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Service\ShortLink\Interfaces;

use Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException;

abstract class ShortLinkAbstractInterface
{
    /**
     * Shortens the long given URL
     *
     * @param string $longUrl
     *
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    abstract public function shorten($longUrl);

    /**
     * Expands the short given URL
     *
     * @param string $shortUrl
     *
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     */
    abstract public function expand($shortUrl);

    /**
     * Validates if the URL to submit actually exists and is accessible.
     *
     * @param $url string                 URL being submitted.
     * @throws \Sonrisa\Service\ShortLink\Exceptions\InvalidApiResponseException
     * @return boolean
     */
    protected function urlCheck($url)
    {
        $opts = array
        (
            'http'=>array
            (
                'method'=>"HEAD",
                'header'=>"Accept-language: en\r\n"
            )
        );

        $context = stream_context_create($opts);

        $fp = @fopen($url, 'r', false, $context);
        @fpassthru($fp);
        @fclose($fp);

        if (empty($http_response_header)) {
            throw new InvalidApiResponseException('URL provided is not a valid resource');
        }

        return
        (
            ($http_response_header[0] == "HTTP/1.1 200 OK") ||
            ($http_response_header[0] == "HTTP/1.0 200 OK") ||
            ($http_response_header[0] == "HTTP/1.0 301 Moved Permanently") ||
            ($http_response_header[0] == "HTTP/1.1 301 Moved Permanently") ||
            ($http_response_header[0] == "HTTP/1.0 301 Moved") ||
            ($http_response_header[0] == "HTTP/1.1 301 Moved") ||
            ($http_response_header[0] == "HTTP/1.1 302 Found") ||
            ($http_response_header[0] == "HTTP/1.0 302 Found")
        );
    }

    /**
     * Performs the Request to the API server.
     *
     * @param $url
     * @param $context
     * @return string
     */
    protected function request($url,$context)
    {
        return file_get_contents($url,false,$context);
    }

}
