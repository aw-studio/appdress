<?php

if (! function_exists('instance_of')) {
    /**
     * Determines if class is instaces of match, by class string.
     *
     * @param  string $class
     * @param  string $match
     * @return bool
     */
    function instance_of(string $class, string $match)
    {
        if ($class === $match) {
            return true;
        }

        if (! class_exists($class) || ! class_exists($match) && ! interface_exists($match)) {
            return false;
        }

        // Match interfaces.
        $implements = (new ReflectionClass($class))->getInterfaceNames();
        if (in_array($match, $implements)) {
            return true;
        }

        // Match parent class.
        $parent = get_parent_class($class);
        if (! $parent) {
            return false;
        }

        if ($parent == $match) {
            return true;
        }

        // Match recursive parent classes.
        return instance_of($parent, $match);
    }
}

if (! function_exists('get_path_from_namespace')) {
    /**
     * Get path from namespace.
     *
     * @param  string $namespace
     * @return string
     */
    function get_path_from_namespace(string $namespace)
    {
        return (new ReflectionClass($namespace))->getFileName();
    }
}

if (! function_exists('class_is_abstract')) {
    /**
     * Get path from namespace.
     *
     * @param  string $class
     * @return string
     */
    function class_is_abstract(string $class)
    {
        return (new ReflectionClass($class))->isAbstract();
    }
}
