<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

/**
 * @brief       myUrlHandlers URL descriptor class.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class UrlDescriptor
{
    /**
     * URL handler callback.
     *
     * @var     ?callable   $handler
     */
    private $handler = null;

    /**
     * Constructor.
     *
     * @param   string      $id                 The ID
     * @param   string      $url                The URL
     * @param   string      $representation     The representation
     * @param   mixed       $handler            The callback
     */
    public function __construct(
        public readonly string $id,
        public readonly string $url = '',
        public readonly string $representation = '',
        mixed $handler = null,
    ) {
        // As PHP does not support callable property type.
        if (is_callable($handler)) {
            $this->handler = $handler;
        }
    }

    /**
     * Get handler.
     *
     * @return  ?callable   The handler
     */
    public function handler(): ?callable
    {
        return $this->handler;
    }
}
