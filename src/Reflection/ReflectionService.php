<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\Reflection;

/**
 * @YAM\Singleton
 */
class ReflectionService
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annotationReader;

    public function __construct(\Doctrine\Common\Annotations\Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * Tells if the specified class has the given annotation
     *
     * @param string $className Name of the class
     * @param string $annotationClassName Annotation to check for
     * @return boolean
     * @api
     */
    public function isClassAnnotatedWith($className, $annotationClassName)
    {
        return !empty($this->getClassAnnotation($className, $annotationClassName));
    }

    /**
     * Returns the specified class annotations or an empty array
     *
     * @param string $className Name of the class
     * @param string $annotationClassName Annotation to filter for
     * @return array
     * @api
     */
    public function getClassAnnotations($className, $annotationClassName = null)
    {
        $classAnnotations = $this->annotationReader->getClassAnnotations(new ClassReflection($className));
        return $this->filterAnnotations($classAnnotations, $annotationClassName);
    }

    /**
     * Returns the specified class annotation or NULL.
     *
     * @param string $className Name of the class
     * @param string $annotationClassName Annotation to filter for
     * @return mixed The Annotation or NULL, if the requested annotation does not exist.
     * @api
     */
    public function getClassAnnotation($className, $annotationClassName)
    {
        return $this->annotationReader->getClassAnnotation(new ClassReflection($className), $annotationClassName);
    }

    /**
     * Tells if the specified method has the given annotation
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @param string $annotationClassName Annotation to check for
     * @return boolean
     * @api
     */
    public function isMethodAnnotatedWith($className, $methodName, $annotationClassName)
    {
        return !empty($this->getMethodAnnotation($className, $methodName, $annotationClassName));
    }

    /**
     * Returns the specified method annotations or an empty array
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @param string $annotationClassName Annotation to filter for
     * @return array
     * @api
     */
    public function getMethodAnnotations($className, $methodName, $annotationClassName = null)
    {
        $methodAnnotations = $this->annotationReader->getMethodAnnotations(new MethodReflection($className, $methodName));
        return $this->filterAnnotations($methodAnnotations, $annotationClassName);
    }

    /**
     * Returns the specified method annotation or NULL.
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @param string $annotationClassName Annotation to filter for
     * @return mixed The Annotation or NULL, if the requested annotation does not exist.
     * @api
     */
    public function getMethodAnnotation($className, $methodName, $annotationClassName)
    {
        return $this->annotationReader->getMethodAnnotation(new MethodReflection($className, $methodName), $annotationClassName);
    }

    /**
     * Tells if the specified method is tagged with the given tag
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @param string $tag Tag to check for
     * @return boolean
     * @api
     */
    public function isMethodTaggedWith($className, $methodName, $tag)
    {
        $method = new MethodReflection($className, $methodName);
        return $method->isTaggedWith($tag);
    }

    /**
     * Returns all tags and their values the specified method is tagged with
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @return array
     * @api
     */
    public function getMethodTags($className, $methodName)
    {
        $method = new MethodReflection($className, $methodName);
        return $method->getTags();
    }


    /**
     * Returns the values of the specified class method tag
     *
     * @param string $className Name of the class
     * @param string $methodName Name of the method
     * @param string $tag Tag to return the values of
     * @return array
     * @api
     */
    public function getMethodTagValues($className, $methodName, $tag)
    {
        $method = new MethodReflection($className, $methodName);
        return $method->getTagValues($tag);
    }

    /**
     * Returns all methods of the given class that have the given annotation
     *
     * @param string $className Name of the class
     * @param string $annotationClassName Annotation to filter for
     * @param int $filter [optional] Filter by access modifiers
     * @return MethodReflection[]
     * @api
     */
    public function getMethodsAnnotatedWith($className, $annotationClassName, $filter = null)
    {
        $clazz = new ClassReflection($className);
        $annotatedMethods = [];

        $methods = $filter === null ? $clazz->getMethods() : $clazz->getMethods($filter);
        foreach ($methods as $method) {
            if (!$method->isConstructor() && $this->isMethodAnnotatedWith($className, $method->getName(), $annotationClassName)) {
                $annotatedMethods[] = $method;
            }
        }
        return $annotatedMethods;
    }

    /**
     * Tells if the specified property has the given annotation
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @param string $annotationClassName Annotation to check for
     * @return boolean
     * @api
     */
    public function isPropertyAnnotatedWith($className, $propertyName, $annotationClassName)
    {
        return !empty($this->getPropertyAnnotation($className, $propertyName, $annotationClassName));
    }

    /**
     * Returns the specified property annotations or an empty array
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @param string $annotationClassName Annotation to filter for
     * @return array
     * @api
     */
    public function getPropertyAnnotations($className, $propertyName, $annotationClassName = null)
    {
        $propertyAnnotations = $this->annotationReader->getPropertyAnnotations(new PropertyReflection($className, $propertyName));
        return $this->filterAnnotations($propertyAnnotations, $annotationClassName);
    }

    /**
     * Returns the specified property annotation or NULL.
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @param string $annotationClassName Annotation to filter for
     * @return mixed The Annotation or NULL, if the requested annotation does not exist.
     * @api
     */
    public function getPropertyAnnotation($className, $propertyName, $annotationClassName)
    {
        return $this->annotationReader->getPropertyAnnotation(new PropertyReflection($className, $propertyName), $annotationClassName);
    }

    /**
     * Tells if the specified property is tagged with the given tag
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @param string $tag Tag to check for
     * @return boolean
     * @api
     */
    public function isPropertyTaggedWith($className, $propertyName, $tag)
    {
        $property = new PropertyReflection($className, $propertyName);
        return $property->isTaggedWith($tag);
    }

    /**
     * Returns all tags and their values the specified property is tagged with
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @return array
     * @api
     */
    public function getPropertyTags($className, $propertyName)
    {
        $property = new PropertyReflection($className, $propertyName);
        return $property->getTags();
    }

    /**
     * Returns the values of the specified class property tag
     *
     * @param string $className Name of the class
     * @param string $propertyName Name of the property
     * @param string $tag Tag to return the values of
     * @return array
     * @api
     */
    public function getPropertyTagValues($className, $propertyName, $tag)
    {
        $property = new PropertyReflection($className, $propertyName);
        return $property->getTagValues($tag);
    }

    /**
     * Returns all properties of the given class that have the given annotation
     *
     * @param string $className Name of the class
     * @param string $annotationClassName Annotation to filter for
     * @param int $filter [optional] Filter by access modifiers
     * @return PropertyReflection[]
     * @api
     */
    public function getPropertiesAnnotatedWith($className, $annotationClassName, $filter = null)
    {
        $clazz = new ClassReflection($className);
        $annotatedProperties = [];

        $properties = $filter === null ? $clazz->getProperties() : $clazz->getProperties($filter);
        foreach ($properties as $property) {
            if ($this->isPropertyAnnotatedWith($className, $property->getName(), $annotationClassName)) {
                $annotatedProperties[] = $property;
            }
        }
        return $annotatedProperties;
    }

    /**
     * @param array $annotations
     * @param string $annotationClassName
     * @return array
     */
    private function filterAnnotations($annotations, $annotationClassName)
    {
        if ($annotationClassName === null) {
            return $annotations;
        } else {
            $filteredAnnotations = [];
            foreach ($annotations as $annotation) {
                if ($annotation instanceof $annotationClassName) {
                    $filteredAnnotations[] = $annotation;
                }
            }
            return $filteredAnnotations;
        }
    }
}