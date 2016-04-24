<?php
namespace cleardevice\SlimMiddlewareAPCCache;

class SlimMiddlewareAPCCache extends \Slim\Middleware
{
    const TTL_KEY = 'apc_ttl';
    const TTL_NO_CACHE = -1;
    const TTL_PERMANENT_CACHE = 0;

    const DEFAULT_TTL = 300;
    const DEFAULT_CACHE_PREFIX = 'SlimAPCCache_';

    protected $ttl;
    protected $cache_prefix;

    public function __construct($ttl = self::DEFAULT_TTL, $prefix = self::DEFAULT_CACHE_PREFIX)
    {
        if (false === extension_loaded('apc') || false === ini_get('apc.enabled')) {
            throw new \Exception('APC not available');
        }

        $this->ttl = $ttl;
        $this->cache_prefix = $prefix;
    }

    public function call()
    {
        $key_name = $this->cache_prefix . $this->app->request()->getResourceUri();
        $response = $this->app->response();

        // Check cache
        if (apc_exists($key_name)) {
            // Return content from cache
            $data = apc_fetch($key_name);

            foreach ($data['header'] as $key => $value) {
                $response->headers->set($key, $value);
            }

            $response->body($data["body"]);

            return true;
        }

        // Not in cache. Call controller
        $this->next->call();

        // Cache the content
        if (($response->status() == 200)) {
            $ttl = $this->app->container->get(self::TTL_KEY, $this->ttl);

            if ($ttl < 0) {
                throw new \Exception('TTL value is invalid');
            }

            if ($ttl == 0) {
                return true;
            }

            $header = $response->headers->all();

            $data = array(
                'header' => $header,
                'body' => $response->body()
            );

            apc_store($key_name, $data, $ttl);
        }

        return true;
    }
}