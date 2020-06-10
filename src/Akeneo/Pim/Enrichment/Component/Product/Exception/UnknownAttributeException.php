<?php

namespace Akeneo\Pim\Enrichment\Component\Product\Exception;

use Akeneo\Pim\Enrichment\Component\Error\Documented\Documentation;
use Akeneo\Pim\Enrichment\Component\Error\Documented\DocumentationCollection;
use Akeneo\Pim\Enrichment\Component\Error\Documented\DocumentedErrorInterface;
use Akeneo\Pim\Enrichment\Component\Error\Documented\HrefMessageParameter;
use Akeneo\Pim\Enrichment\Component\Error\Documented\RouteMessageParameter;
use Akeneo\Pim\Enrichment\Component\Error\DomainErrorInterface;
use Akeneo\Tool\Component\StorageUtils\Exception\PropertyException;

/**
 * Exception thrown when performing an action on an unknown attribute.
 *
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class UnknownAttributeException extends PropertyException implements
    DomainErrorInterface,
    DocumentedErrorInterface
{
    /** @var DocumentationCollection */
    private $documentation;

    public function __construct(string $attributeName, string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->propertyName = $attributeName;
        $this->documentation = $this->buildDocumentation();
    }

    public static function unknownAttribute(string $attributeCode, \Exception $previous = null): self
    {
        return new static(
            $attributeCode,
            sprintf(
                'Attribute "%s" does not exist.',
                $attributeCode
            ),
            0,
            $previous
        );
    }

    public function getDocumentation(): DocumentationCollection
    {
        return $this->documentation;
    }

    private function buildDocumentation(): DocumentationCollection
    {
        return new DocumentationCollection([
            new Documentation(
                'More information about attributes: {what_is_attribute} {manage_attribute}.',
                [
                    'what_is_attribute' => new HrefMessageParameter(
                        'What is an attribute?',
                        'https://help.akeneo.com/pim/serenity/articles/what-is-an-attribute.html'
                    ),
                    'manage_attribute' => new HrefMessageParameter(
                        'Manage your attributes',
                        'https://help.akeneo.com/pim/serenity/articles/manage-your-attributes.html'
                    )
                ]
            ),
            new Documentation(
                'Please check your {attribute_settings}.',
                [
                    'attribute_settings' => new RouteMessageParameter(
                        'Attributes settings',
                        'pim_enrich_attribute_index'
                    )
                ]
            )
        ]);
    }
}