<?php

declare(strict_types=1);

namespace SimpleUserManager\Policy;

use FilterIterator;
use Iterator;

/**
 * This class filters out all elements from a route policy configuration array,
 * except for one where the key matches the current route.
 *
 * @template TKey of string
 * @template TValue of string
 * @template TIterator of Iterator
 * @template-extends FilterIterator<TKey, TValue, TIterator>
 */
class RoutePolicyFilterIterator extends FilterIterator
{
    public function __construct(Iterator $iterator, private readonly string $currentRoute)
    {
        parent::__construct($iterator);
    }

    /**
     * @inheritDoc
     */
    public function accept(): bool
    {
        return $this->currentRoute === $this->getInnerIterator()->key();
    }
}
