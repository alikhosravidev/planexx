<?php

namespace App\Services;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;

class ResourceRegistrar extends BaseResourceRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'keyValList'];

    public function register($name, $controller, array $options = [])
    {
        if (isset($options['only'])) {
            $options['only'] = array_merge((array) $options['only'], ['keyValList']);
        }

        return parent::register($name, $controller, $options);
    }

    protected function addResourceKeyValList($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/key-value-list/{field}/{key?}';

        $action = $this->getResourceAction($name, $controller, 'keyValList', $options);

        return $this->router->get($uri, $action);
    }
}
