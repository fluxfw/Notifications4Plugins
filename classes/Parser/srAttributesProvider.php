<?php


/**
 * Interface srAttributesProvider
 */
interface srAttributesProvider
{

    /**
     * Return an attribute by the given name as string
     * If no attribute exists, return null
     *
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name);

}