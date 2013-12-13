<?php

namespace MagdKudama\CustomRouteBundle\Routing\BasicRest;

use MagdKudama\CustomRouteBundle\Routing\Utils;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class BasicRestLoader extends FileLoader
{
    const ROUTE_TYPE = 'basic_rest';
    const INTERFACE_TO_IMPLEMENT = "MagdKudama\\CustomRouteBundle\\Routing\\BasicRest\\BasicRestLoaderInterface";

    public function load($file, $type = null)
    {
        $filePath = $this->getLocator()->locate($file);

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not found.', $filePath));
        }

        $namespace = Utils::getNamespaceByFilePath($filePath);
        $class = new \ReflectionClass($namespace);

        if (!Utils::implementsInterface($class->getInterfaces(), self::INTERFACE_TO_IMPLEMENT)) {
            throw new \InvalidArgumentException(sprintf('Class %s does not implement %s interface.', $namespace, self::INTERFACE_TO_IMPLEMENT));
        }

        $routePrefix = Utils::getBaseRouteNameByClassFqn($namespace);
        $routesToLoad = Yaml::parse(file_get_contents(__DIR__ . '/valid-routes.yml'));

        $collection = new RouteCollection();
        foreach ($routesToLoad as $routeId => $options) {
            $route = new Route(
                Utils::guessRoutePath($class, $options),
                Utils::getCallbackController($class, $options),
                Utils::getRequirementsArray($options),
                [], '', [],
                $options['methods_allowed']
            );
            $collection->add(sprintf("%s_%s", $routePrefix, $routeId), $route);
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return self::ROUTE_TYPE === $type;
    }
}