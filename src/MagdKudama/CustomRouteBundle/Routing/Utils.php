<?php

namespace MagdKudama\CustomRouteBundle\Routing;

/**
 * Generic utils class to ease working with routes and PSR
 * @package MagdKudama\CustomRouteBundle\Routing
 */
class Utils
{
    /**
     * Returns the class FQN given the absolute file path
     * @param $path
     * @return mixed
     */
    public static function getNamespaceByFilePath($path)
    {
        $fqn = substr($path, strpos($path, 'src') + 3);
        $fqn = str_replace("/", "\\", $fqn);
        $fqn = str_replace(".php", "", $fqn);

        return $fqn;
    }

    /**
     * Checks if a given class implements an interface
     * @param array $interfaces
     * @param $interfaceToImplement
     * @return bool
     */
    public static function implementsInterface(array $interfaces, $interfaceToImplement)
    {
        foreach ($interfaces as $interface) {
            if ($interface->name === $interfaceToImplement) {
                return true;
            }
        }

        return false;
    }

    /**
     * Given a namespace, this methods converts it to a route name (basic route name, an identifier should be added)
     * @param $class
     * @return string
     */
    public static function getBaseRouteNameByClassFqn($class)
    {
        $routeName = strtolower($class);
        $routeName = str_replace("controller", "", $routeName);
        $routeName = str_replace("bundle", "", $routeName);
        $routeName = str_replace("\\\\", "\\", $routeName);
        $routeName = str_replace("\\", "_", $routeName);

        return substr($routeName, 1);
    }

    /**
     * Given a controller name, this method returns the path
     * @param $controller
     * @return string
     * @example ClientController => client
     */
    public static function getPathByControllerName($controller)
    {
        return "/" . str_replace("controller", "", strtolower($controller));
    }

    /**
     * Returns the route path adding extra parameters
     * @param \ReflectionClass $class
     * @param array $parameters
     * @return string
     */
    public static function guessRoutePath(\ReflectionClass $class, array $parameters)
    {
        $routePath = Utils::getPathByControllerName($class->getShortName());
        if (isset($parameters['extra'])) {
            $routePath .= '/' . $parameters['extra'];
        }

        return $routePath;
    }

    /**
     * Returns requirements array, given parameters
     * @param array $parameters
     * @return array
     */
    public static function getRequirementsArray(array $parameters)
    {
        $requirements = [];
        if (isset($parameters['parameters'])) {
            foreach ($parameters['parameters'] as $parameter) {
                $requirements[$parameter['name']] = $parameter['requirement'];
            }
        }

        return $requirements;
    }

    /**
     * Gets the callback by class and parameters array
     * @param \ReflectionClass $class
     * @param array $parameters
     * @return array
     */
    public static function getCallbackController(\ReflectionClass $class, array $parameters)
    {
        return ['_controller' => sprintf("%s::%s", $class->getName(), $parameters['callback_action'])];
    }

}