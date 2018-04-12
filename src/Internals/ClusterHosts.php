<?php

namespace Algolia\AlgoliaSearch\Internals;

class ClusterHosts
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->assertHostsAreValid($hosts);

        $this->hosts = $hosts;
    }

    public static function createFromAppId($applicationId)
    {
        $read = $write = [
            $applicationId.'-1.algolianet.com',
            $applicationId.'-2.algolianet.com',
            $applicationId.'-3.algolianet.com',
        ];

        shuffle($read);
        array_unshift($read, $applicationId.'-dsn.algolia.net');

        shuffle($write);
        array_unshift($write, $applicationId.'.algolia.net');

        $hosts = [
            'read' => $read,
            'write' => $write,
        ];

        return new static($hosts);
    }

    public static function createForPlaces()
    {
        $read = $write = [
            'places-1.algolianet.com',
            'places-2.algolianet.com',
            'places-3.algolianet.com',
        ];

        shuffle($read);
        array_unshift($read, 'places-dsn.algolia.net');

        shuffle($write);
        array_unshift($write, 'places-dsn.algolia.net');

        $hosts = [
            'read' => $read,
            'write' => $write,
        ];

        return new static($hosts);
    }

    public function read()
    {
        return $this->hosts['read'];
    }

    public function write()
    {
        return $this->hosts['write'];
    }

    public function failed($host)
    {
        $writeIndex = array_search($host, $this->hosts['write']);
        $readIndex = array_search($host, $this->hosts['read']);

        if (false !== $writeIndex) {
            unset($this->hosts['write'][$writeIndex]);
        }

        if (false !== $readIndex) {
            unset($this->hosts['read'][$readIndex]);
        }

        return $this;
    }

    private function assertHostsAreValid($hosts)
    {
        foreach (['read', 'write'] as $action) {
            if (!(isset($hosts[$action]) && is_array($hosts[$action]))) {
                throw new \Exception('hosts array passed to '.self::class.' is invalid');
            }
        }
    }
}