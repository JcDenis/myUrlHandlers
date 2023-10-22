<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

/**
 * @brief       myUrlHandlers URL descriptor stack class.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class UrlStack
{
    /**
     * The stack of URL descriptors.
     *
     * @var     array<string, UrlDescriptor>
     */
    private array $stack = [];

    /**
     * Check if an handler is set.
     *
     * @param   string  $id     The handler ID
     *
     * @return  bool    True if it exists
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->stack);
    }

    /**
     * Set an URL handler.
     *
     * @param   UrlDescriptor   $descriptor     The URL descriptor
     */
    public function set(UrlDescriptor $descriptor): void
    {
        $this->stack[$descriptor->id] = $descriptor;
    }

    /**
     * Get an URL handler.
     *
     * If it does not exist, return an empty handler.
     *
     * @param   string  $id     The handler ID
     *
     * @return  UrlDescriptor   The URL descriptor
     */
    public function get(string $id): UrlDescriptor
    {
        return $this->stack[$id] ?? new UrlDescriptor($id);
    }

    /**
     * Get URLs stack.
     *
     * @return  array<string, UrlDescriptor>
     */
    public function dump()
    {
        return $this->stack;
    }
}