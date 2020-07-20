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
