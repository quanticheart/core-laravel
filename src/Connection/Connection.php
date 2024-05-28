<?php

namespace Quanticheart\Laravel\Connection;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXpath;
use RuntimeException;

/**
 * Class CurlHelper
 */
class Connection
{
    /**
     * @var string
     */
    public string $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36';

    /**
     * @var int
     */
    public int $timeout = 60;

    /**
     * @var resource
     */
    protected $ch;

    /**
     * @var null|string
     */
    protected ?string $url;

    /**
     * @var array
     */
    protected array $get_data = [];

    /**
     * @var array
     */
    protected array $post_data = [];

    /**
     * @var null|string
     */
    protected ?string $post_raw;

    /**
     * @var array
     */
    protected array $cookies = [];

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array
     */
    protected array $xpath = [];

    /**
     * @var array
     */
    protected array $files = [];


    const MIME_X_WWW_FORM = 'application/x-www-form-urlencoded';
    const MIME_FORM_DATA = 'multipart/form-data';
    const MIME_JSON = 'application/json';


    /**
     * @param string|null $url [optional]
     * @return Connection
     */
    public static function factory(string $url = null): Connection
    {
        return new self($url);
    }

    /**
     * @param string|null $url [optional]
     */
    public function __construct(string $url = null)
    {
        $this->ch = curl_init();
        $this->url = $url;
    }

    /**
     * Set config
     * @param $config
     * @return $this
     */
    public function config($config): static
    {
        $alias = [
            'url' => 'setUrl',
            'userAgent' => 'setUserAgent',
            'user-agent' => 'setUserAgent',
            'timeout' => 'setTimeout',
            'postRaw' => 'setPostRaw',
            'post-raw' => 'setPostRaw',
            'postParams' => 'setPostParams',
            'postFields' => 'setPostParams',
            'post-params' => 'setPostParams',
            'post-fields' => 'setPostParams',
            'post' => 'setPostParams',
            'getParams' => 'setGetParams',
            'getFields' => 'setGetParams',
            'get-params' => 'setGetParams',
            'get-fields' => 'setGetParams',
            'get' => 'setGetParams',
            'headers' => 'setHeaders',
            'cookies' => 'setCookies',
            'cookieFile' => 'setCookieFile',
            'cookie-file' => 'setCookieFile',
            'proxy' => 'useProxy',
            'options' => 'setOptions',
            'file' => 'putFile',
            'fileRaw' => 'putFileRaw',
            'file-raw' => 'putFileRaw',
        ];
        $config = array_intersect_key($config, array_fill_keys(array_merge(array_keys($alias), array_values($alias), [
            'follow', 'xpath',
        ]), 1));
        foreach ($config as $method => $value) {
            if (isset($alias[$method])) {
                $method = $alias[$method];
            }
            if (!(in_array($method, ['useProxy', 'putFile', 'putFileRaw']) && is_array($value))) {
                $value = [$value];
            }
            call_user_func_array([$this, $method], $value);
        }
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param bool $follow [optional]
     * @return $this
     */
    public function follow(bool $follow = true): static
    {
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $follow);
        return $this;
    }

    /**
     * @param string $ua
     * @return $this
     */
    public function setUserAgent(string $ua): static
    {
        $this->user_agent = $ua;
        return $this;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param bool|string $debug [optional]
     * @return $this
     */
    public function debug(bool|string $debug = true): static
    {
        curl_setopt($this->ch, CURLOPT_VERBOSE, $debug && true);
        if (is_string($debug)) {
            curl_setopt($this->ch, CURLOPT_STDERR, $debug);
        }
        return $this;
    }

    /**
     * @param string $raw
     * @return $this
     */
    public function setPostRaw(string $raw): static
    {
        $this->post_raw = $raw;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setPostParams(array $data): static
    {
        $this->post_data = array_merge($this->post_data, $data);
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @see Connection::setPostParams() :similar:
     */
    public function setPostFields(array $data): static
    {
        return $this->setPostParams($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setGetParams($data): static
    {
        $this->get_data = array_merge($this->get_data, $data);
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @see Connection::setGetParams() :similar:
     */
    public function setGetFields(array $data): static
    {
        return $this->setGetParams($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setHeaders($data)
    {
        foreach ($data as $key => $val) {
            $this->headers[self::fixStringCase($key)] = $val;
        }
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setCookies($data)
    {
        $this->cookies = array_merge($this->cookies, $data);
        return $this;
    }

    /**
     * @param string $fieldname
     * @param string $filename
     * @param string|null $basename
     * @param string|null $mime_type
     * @return $this
     */
    public function putFile($fieldname, $filename, $basename = null, $mime_type = null)
    {
        $this->files[] = [
            'type' => 'file',
            'fieldname' => $fieldname,
            'file' => $filename,
            'basename' => $basename,
            'mime_type' => $mime_type,
        ];
        return $this;
    }

    /**
     * @param string $fieldname
     * @param string $file_contents
     * @param string $basename
     * @param string $mime_type
     * @return $this
     */
    public function putFileRaw($fieldname, $file_contents, $basename, $mime_type)
    {
        $this->files[] = [
            'type' => 'raw',
            'fieldname' => $fieldname,
            'file' => $file_contents,
            'basename' => $basename,
            'mime_type' => $mime_type,
        ];
        return $this;
    }

    /**
     * @return string
     */
    protected function generateUrl()
    {
        $parsed_string = '';
        $url = parse_url($this->url);
        if (!empty($url['query'])) {
            parse_str($url['query'], $get_data);
            $url['query'] = http_build_query(array_merge($get_data, $this->get_data));
        } else {
            $url['query'] = http_build_query($this->get_data);
        }
        if (isset($url['scheme'])) {
            $parsed_string .= $url['scheme'] . '://';
        }
        if (isset($url['user'])) {
            $parsed_string .= $url['user'];
            if (isset($url['pass'])) {
                $parsed_string .= ':' . $url['pass'];
            }
            $parsed_string .= '@';
        }
        if (isset($url['host'])) {
            $parsed_string .= $url['host'];
        }
        if (isset($url['port'])) {
            $parsed_string .= ':' . $url['port'];
        }
        if (!empty($url['path'])) {
            $parsed_string .= $url['path'];
        } else {
            $parsed_string .= '/';
        }
        if (!empty($url['query'])) {
            $parsed_string .= '?' . $url['query'];
        }
        if (isset($url['fragment'])) {
            $parsed_string .= '#' . $url['fragment'];
        }

        return $parsed_string;
    }

    /**
     * @param $filename
     * @return $this
     */
    public function setCookieFile($filename)
    {
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $filename);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $filename);
        return $this;
    }

    /**
     * @param string $host
     * @param string|null $login [optional]
     * @param string|null $password [optional]
     * @return $this
     */
    public function useProxy($host, $login = null, $password = null)
    {
        curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($this->ch, CURLOPT_PROXY, $host);
        if (isset($login)) {
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, "$login:$password");
        }
        return $this;
    }

    /**
     * Set custom CURL options
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            curl_setopt($this->ch, $key, $value);
        }
        return $this;
    }

    /**
     * Set extended XPath
     * @param string|string[] $expr
     * @return $this
     */
    public function xpath(array|string $expr): static
    {
        $this->xpath = $expr;
        return $this;
    }

    /**
     * Execute
     * @param string|null $returnData [optional]
     * @return array|null
     */
    public function exec(string $returnData = null): ?array
    {
        if (isset($this->post_raw)) {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->post_raw);
            $this->headers['Content-Length'] = strlen($this->post_raw);
            if (empty($this->headers['Content-Type'])) {
                $this->headers['Content-Type'] = 'text/plain';
            }
        } elseif (!empty($this->post_data) || !empty($this->files)) {
            curl_setopt($this->ch, CURLOPT_POST, 1);

            if (!empty($this->files)) {
                $this->headers['Content-Type'] = self::MIME_FORM_DATA;
            } elseif (empty($this->headers['Content-Type'])) {
                $this->headers['Content-Type'] = self::MIME_X_WWW_FORM;
            }

            if ($this->headers['Content-Type'] === self::MIME_JSON) {
                $data = json_encode($this->post_data);
            } elseif ($this->headers['Content-Type'] === self::MIME_FORM_DATA) {
                $data = $this->generateBoundary();
            } else {
                $data = http_build_query($this->post_data);
            }

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            $this->headers['Content-Length'] = strlen($data);
        }

        if (!empty($this->headers)) {
            $data = [];
            foreach ($this->headers as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $val) {
                        $data[] = "$k: $val";
                    }
                } else {
                    $data[] = "$k: $v";
                }
            }
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $data);
        }

        if (!empty($this->cookies)) {
            $data = [];
            foreach ($this->cookies as $k => $v) {
                $data[] = "$k=$v";
            }
            curl_setopt($this->ch, CURLOPT_COOKIE, implode('; ', $data));
        }

        $url = $this->generateUrl();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);

        return $this->generateResponse($url, $returnData);
    }

    /**
     * Generate boundary
     * @return string
     */
    protected function generateBoundary(): string
    {
        $eol = PHP_EOL;
        $boundary = '----CurlHelperBoundary' . md5(microtime());

        $this->headers['Content-Type'] = self::MIME_FORM_DATA . "; boundary=$boundary";

        $data = [];
        $each = function ($field, $value) use (&$data, &$each, $boundary, $eol) {
            if (is_array($value)) {
                if (empty($value)) {
                    $each("{$field}[]", '');
                } else {
                    foreach ($value as $key => $item) {
                        if (is_int($key)) $key = '';
                        $each("{$field}[$key]", $item);
                    }
                }
            } else {
                $data[] = "--$boundary$eol";
                $data[] = "Content-Disposition: form-data; name=\"$field\"$eol$eol";
                $data[] = "$value$eol";
            }
        };
        foreach ($this->post_data as $field => $value) {
            $each($field, $value);
        }

        foreach ($this->files as $file) {
            if ($file['type'] === 'file') {
                if (empty($file['basename'])) {
                    $file['basename'] = basename($file['file']);
                }
                if (empty($file['mime_type'])) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $file['mime_type'] = finfo_file($finfo, $file['file']);
                    finfo_close($finfo);
                }
            }
            if (empty($file['mime_type'])) {
                $file['mime_type'] = 'application/octet-stream';
            }
            $data[] = "--$boundary$eol";
            $data[] = "Content-Disposition: form-data; name=\"{$file['fieldname']}\"; filename=\"{$file['basename']}\"$eol";
            $data[] = "Content-Type: {$file['mime_type']}$eol$eol";
            $data[] = ($file['type'] === 'file' ? file_get_contents($file['file']) : $file['file']) . $eol;
        }

        $data[] = "--$boundary--$eol$eol";

        return implode('', $data);
    }

    /**
     * Generate response
     * @param string $url
     * @param null $returnData [optional]
     * @return array|null
     */
    protected function generateResponse(string $url, $returnData = null): ?array
    {
        $response = curl_exec($this->ch);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $sent = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
        $status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $error = curl_error($this->ch);
        $errno = curl_errno($this->ch);

        curl_close($this->ch);

        if ($response === false) {
            throw new RuntimeException($error, $errno);
        }

        $header = substr($response, 0, $header_size);
        $content = substr($response, $header_size);
        $type = $json_data = null;

        $headers = [];
        $cookies = [];

        if (!$returnData || in_array($returnData, ['content', 'data', 'xpath', 'headers', 'cookies'])) {
            foreach (explode("\n", $header) as $line) {
                $line = explode(':', $line, 2);
                if (isset($line[1])) {
                    list($key, $value) = $line;
                    $key = self::fixStringCase($key);
                    $value = ($value = trim($value)) && !empty($value) ? $value : null;
                    if (isset($headers[$key]) && $headers[$key] != $value) {
                        if (!is_array($headers[$key])) {
                            $headers[$key] = [$headers[$key]];
                        }
                        $headers[$key][] = $value;
                    } else {
                        $headers[$key] = $value;
                    }
                }
            }
        }

        if (!$returnData || $returnData === 'cookies') {
            if (isset($headers['Set-Cookie'])) {
                foreach (is_array($headers['Set-Cookie']) ? $headers['Set-Cookie'] : [$headers['Set-Cookie']] as $cookie) {
                    $cookie = explode('=', explode(';', $cookie, 2)[0], 2);
                    if (isset($cookie[1])) {
                        $cookies[$cookie[0]] = $cookie[1];
                    }
                }
            }
        }

        if (!$returnData) {
            $type = isset($headers['Content-Type']) ? is_array($headers['Content-Type']) ?
                $headers['Content-Type'][0] : $headers['Content-Type'] : null;
        }

        if (!$returnData || in_array($returnData, ['content', 'data', 'xpath'])) {
            $encoding = isset($headers['Content-Encoding']) ? is_array($headers['Content-Encoding']) ?
                $headers['Content-Encoding'][0] : $headers['Content-Encoding'] : null;
            $content = strtolower($encoding) === 'gzip' ? gzdecode($content) : $content;
        }

        if (!$returnData || $returnData === 'data') {
            $json_data = !empty($content) && in_array($content[0], ['{', '[']) ? json_decode($content, true) : false;
        }

        if (!$returnData || $returnData === 'headers') {
            ksort($headers);
        }

        $data = [
            'status' => $status,
            'url' => $url,
            'type' => $type,
            'headers' => $headers,
            'cookies' => $cookies,
            'headers_raw' => $header,
            'content' => $content,
            'data' => $json_data,
        ];

        if (!empty($this->xpath) && (!$returnData || $returnData === 'xpath')) {
            $data['xpath'] = $this->parseXpath($content);
        }

        if (!empty($sent)) {
            $data['request'] = $sent;
        }

        return $returnData ? $data[$returnData] : $data;
    }

    /**
     * Parse XPath contents
     * @param string $content
     * @return array|null
     * @noinspection PhpConditionAlreadyCheckedInspection
     */
    protected function parseXpath(string $content): ?array
    {
        if (!empty($this->xpath) && !empty($content)) {
            libxml_use_internal_errors(true);
            $func = [
                'node' => '/\/node\(\)$/',
                'name' => '/\/name\(\)$/',
                'html' => '/\/html\(\)$/',
                'trim' => '/\/trim\(\)$/',
                'name_value' => '/\/\(name,value\)$/',
            ];

            $aliases = [
                '/@([a-zA-Z0-9\-_]+)~=(["\'])([a-zA-Z0-9\x20\-_]+)\2/' => 'contains(concat(\2 \2, @\1, \2 \2), \2 \3 \2)',
            ];

            $aliases = array_merge(array_fill_keys(array_values($func), ''), $aliases);

            $replace_aliases = function ($query) use ($aliases) {
                foreach ($aliases as $regexp => $replace) {
                    $query = preg_replace($regexp, $replace, $query);
                }
                return $query;
            };

            $getNodeValue = function ($doc, $query) use ($replace_aliases, $func) {
                /** @var DOMDocument $doc */
                $result = [];
                $xpath = new DOMXpath($doc);

                $is_node = preg_match($func['node'], $query);
                $is_name = preg_match($func['name'], $query);
                $is_html = preg_match($func['html'], $query);
                $is_trim = preg_match($func['trim'], $query);
                $is_nameVal = preg_match($func['name_value'], $query);

                $query = $replace_aliases($query);
                $nodes = $xpath->query($query);

                if ($nodes instanceof DOMNodeList) {
                    foreach ($nodes as $node) {
                        /** @var DOMElement $node */
                        if ($is_node) {
                            $result[] = $node;
                        } else {
                            if ($is_html) {
                                $value = $doc->saveHTML($node);
                            } elseif ($is_name) {
                                $value = $node->nodeName;
                            } elseif ($is_nameVal) {
                                $value = [
                                    'name' => $node->nodeName,
                                    'value' => $node->nodeValue,
                                ];
                            } else {
                                $value = $node->nodeValue;
                            }

                            if ($is_trim) {
                                $value = trim($value);
                            }

                            $result[] = $value;
                        }
                    }
                }

                return $result;
            };

            $doc = new DOMDocument();
            $doc->loadHTML($content);
            $result = [];

            if (is_array($this->xpath)) {
                foreach ($this->xpath as $id => $query) {
                    if (is_array($query)) {
                        $res = [];
                        foreach ($query as $root => $sub) {
                            if (is_array($sub)) {
                                $r = $x = [];
                                $max = 0;
                                foreach ($sub as $j => $q) {
                                    $r[$j] = $getNodeValue($doc, "$root$q");
                                    $max = max($max, count($r[$j]));
                                }
                                for ($i = 0; $i < $max; $i++) {
                                    $x[$i] = [];
                                    foreach ($sub as $j => $q) {
                                        $x[$i][$j] = $r[$j][$i] ?? null;
                                    }
                                }
                                $res[] = $x;
                            } else {
                                $res[] = $getNodeValue($doc, "$root$sub");
                            }
                        }
                        $result[$id] = count($res) === 1 ? $res[0] : $res;
                    } else {
                        $result[$id] = $getNodeValue($doc, $query);
                    }
                }
            } else {
                $result = $getNodeValue($doc, $this->xpath);
            }
            libxml_use_internal_errors(false);
            return $result;
        }
        return is_array($this->xpath) ? array_fill_keys(array_keys($this->xpath), []) : [];
    }

    /**
     * Fix strings to Proper-Case
     * @param string $str
     * @return string
     */
    protected static function fixStringCase(string $str): string
    {
        $str = explode('-', $str);
        foreach ($str as &$word) {
            $word = ucfirst($word);
        }
        return implode('-', $str);
    }
}
