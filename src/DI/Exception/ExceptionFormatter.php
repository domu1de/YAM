<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2014 Domenic Muskulus
 * @license    MIT
 */

namespace YAM\DI\Exception;


use YAM\DI\Activation\Request;
use YAM\DI\Planning\Module;
use YAM\Utility\StringBuilder;

/**
 * Provides meaningful exception messages.
 *
 * @package YAM\DI\Exception
 */
class ExceptionFormatter
{
    /**
     * Generates a message saying that a module with the same name is already loaded.
     *
     * @param \YAM\DI\Planning\Module $newModule
     * @return string
     */
    public static function moduleWithSameNameIsAlreadyLoaded(Module $newModule)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error loading module \'%s\': another module with the same name has already been loaded', $newModule->getName());
        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure that you have not accidentally loaded the same module twice.');

        return (string) $sb;
    }

    /**
     * Generates a message saying that no module has been loaded with the specified name.
     *
     * @param string $name
     * @return string
     */
    public static function noModuleLoadedWithTheSpecifiedName($name)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error unloading module \'%s\': no such module has been loaded', $name);
        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure you have previously loaded the module and the name is spelled correctly.');
        $sb->appendLine('  2) Ensure you have not accidentally created more than one object-manager.');

        return (string) $sb;
    }

    /**
     * Generates a message saying that the specified module is of an unsupported type.
     *
     * @param mixed $module
     * @return string
     */
    public static function unsupportedModuleType($module)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error loading module \'%s\': Module must be a subtype of \'%s\'', get_class($module), Module::class);

        return (string) $sb;
    }

    /**
     * Generates a message saying that the binding could not be uniquely resolved.
     *
     * @param \YAM\DI\Activation\Request $request
     * @param \YAM\DI\Planning\Bindings\Binding[] $matchingBindings
     * @return string
     */
    public static function couldNotUniquelyResolveBinding(Request $request, array $matchingBindings)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error activating %s', $request->getService());
        $sb->appendLine('More than one matching bindings are available.');

        $sb->appendLine('Matching bindings:');
        foreach ($matchingBindings as $i => $binding) {
            $sb->appendLine('  %s) %s', $i, $binding);
        }

        $sb->appendLine('Activation path:');
        $sb->appendLine(self::formatActivationPath($request));

        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure that you have defined a binding for %s only once.', $request->getService());

        return (string) $sb;
    }

    /**
     * Generates a message saying that the binding could not be resolved on the specified request.
     *
     * @param \YAM\DI\Activation\Request $request
     * @return string
     */
    public static function couldNotResolveBinding(Request $request)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error activating %s', $request->getService());
        $sb->appendLine('No matching bindings are available, and the type is not self-bindable.');

        $sb->appendLine('Activation path:');
        $sb->appendLine(self::formatActivationPath($request));

        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure that you have defined a binding for %s.', $request->getService());
        $sb->appendLine('  2) If the binding was defined in a module, ensure that the module has been loaded into the object-manager.');
        $sb->appendLine('  3) Ensure you have not accidentally created more than one object-manager.');
        $sb->appendLine('  4) If you are using constructor arguments, ensure that the parameter name matches the constructors parameter name.');

        return (string) $sb;
    }

    /**
     * Generates a message saying that the specified context has circular dependencies.
     *
     * @param \YAM\DI\Activation\Context $context
     * @return string
     */
    public static function circularDependenciesDetected($context)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error activating %s using %s', $context->getRequest()->getService(), $context->getBinding());
        $sb->appendLine('A circular dependency was detected between the constructors of two services.');

        $sb->appendLine('Activation path:');
        $sb->appendLine(self::formatActivationPath($context->getRequest()));

        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure that you have not declared a dependency for %s on any implementations of the service.', $context->getRequest()->getService());
        $sb->appendLine('  2) Consider combining the services into a single one to remove the cycle.');
        $sb->appendLine('  3) Use property injection instead of constructor injection, and implement IInitializable');
        $sb->appendLine('     if you need initialization logic to be run after property values have been injected.');

        return (string) $sb;
    }

    /**
     * Generates a message saying that no constructors are available on the specified context.
     *
     * @param \YAM\DI\Activation\Context $context
     * @return string
     */
    public static function noConstructorsAvailable($context)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error activating %s using %s', $context->getRequest()->getService(), $context->getBinding());
        $sb->appendLine('No constructor was available to create an instance of the implementation type.');

        $sb->appendLine('Activation path:');
        $sb->appendLine(self::formatActivationPath($context->getRequest()));

        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) Ensure that the implementation type has a public constructor.');
        $sb->appendLine('  2) If you have implemented the Singleton pattern, use a binding with inSingletonScope() instead.');

        return (string) $sb;
    }

    /**
     * Generates a message saying that the specified component is not registered.
     *
     * @param string $component
     * @return string
     */
    public static function noSuchComponentRegistered($component)
    {
        $sb = new StringBuilder();
        $sb->appendLine('Error loading YAM-DI component %s', $component);
        $sb->appendLine('No such component has been registered in the object-manager\'s component container.');
        $sb->appendLine();

        $sb->appendLine('Suggestions:');
        $sb->appendLine('  1) If you have created a custom subclass for ObjectManager, ensure that you have properly');
        $sb->appendLine('     implemented the addComponents() method.');
        $sb->appendLine('  2) Ensure that you have not removed the component from the container via a call to removeAll().');
        $sb->appendLine('  3) Ensure you have not accidentally created more than one object-manager.');

        return (string) $sb;
    }

    /**
     * Formats the activation path into a meaningful string representation.
     *
     * @param \YAM\DI\Activation\Request $request
     * @return string
     */
    private static function formatActivationPath($request)
    {
        $sb = new StringBuilder();
        $current = $request;

        while ($current !== null) {
            $sb->appendLine('%3s) %s', $current->getDepth() + 1, $current);
            $current = $current->getParentRequest();
        }

        return (string) $sb;
    }
}