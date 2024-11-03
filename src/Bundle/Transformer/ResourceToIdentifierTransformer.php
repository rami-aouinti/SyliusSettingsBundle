<?php

declare(strict_types=1);

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SettingsBundle\Transformer;

use Sylius\Bundle\SettingsBundle\Resource\RepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class ResourceToIdentifierTransformer implements DataTransformerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @param string $identifier
     */
    public function __construct(RepositoryInterface $repository, $identifier = 'id')
    {
        $this->repository = $repository;
        $this->identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value): mixed
    {
        if (!is_object($value)) {
            return null;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($value, $this->identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ('id' === $this->identifier) {
            return $this->repository->find($value);
        }

        return $this->repository->findOneBy([$this->identifier => $value]);
    }
}
