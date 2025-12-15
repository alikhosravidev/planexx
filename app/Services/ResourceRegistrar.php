<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;

class ResourceRegistrar extends BaseResourceRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'keyValList'];

    public function register($name, $controller, array $options = [])
    {
        $only   = (array) ($options['only'] ?? []);
        $except = (array) ($options['except'] ?? []);

        $isApiResource = $this->isApiResource($only, $except);

        if ($isApiResource) {
            if (! empty($only) && ! in_array('keyValList', $only)) {
                $options['only'] = array_merge($only, ['keyValList']);
            }
        } else {
            $options['except'] = array_merge($except, ['keyValList']);
        }

        return parent::register($name, $controller, $options);
    }

    protected function isApiResource(array $only, array $except): bool
    {
        $apiDefaults = ['index', 'store', 'show', 'update', 'destroy'];

        if (! empty($only)) {
            return empty(array_diff(array_diff($only, ['keyValList']), $apiDefaults));
        }

        return in_array('create', $except) && in_array('edit', $except);
    }

    protected function addResourceKeyValList($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/key-value-list/{field}/{key?}';

        $action = $this->getResourceAction($name, $controller, 'keyValList', $options);

        return $this->router->get($uri, $action);
    }
}
