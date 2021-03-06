<?php
/**
 * This source file is proprietary and part of Rebilly.
 *
 * (c) Rebilly SRL
 *     Rebilly Ltd.
 *     Rebilly Inc.
 *
 * @see https://www.rebilly.com
 */

namespace Rebilly\Rest;

use ArrayAccess;
use RuntimeException;

/**
 * Class Factory
 *
 */
final class Factory
{
    /** @var callable[] */
    private $builders = [];

    /** @var array */
    private $patterns = [];

    /**
     * Create new factory.
     *
     * @param ArrayAccess $builders
     */
    public function __construct(ArrayAccess $builders)
    {
        $this->builders = $builders;
        $this->generatePatterns();
    }

    /**
     * Generate regex patterns for URI templates.
     */
    private function generatePatterns()
    {
        foreach ($this->builders as $uri => $builder) {
            $pattern = '/' . ltrim($uri, '/');

            if (preg_match_all('/{[\w]+}/i', $uri, $matches)) {
                foreach (array_unique($matches[0]) as $match) {
                    $pattern = str_replace($match, '[\w\d\@_~\-\.]+', $pattern);
                }
            }

            $this->patterns['#^' . $pattern . '$#i'] = $uri;
        }

        // Sort patterns to ensure that longer more precisely pattern comes first
        krsort($this->patterns);
    }

    /**
     * Create resource object by URI template and resource data.
     *
     * @param string $uri
     * @param array $content
     *
     * @throws RuntimeException
     *
     * @return mixed|Collection|Entity|Entity[]
     */
    public function create($uri, $content)
    {
        $uri = '/' . trim($uri, '/');

        foreach ($this->patterns as $pattern => $key) {
            if (preg_match($pattern, $uri)) {
                return call_user_func($this->builders[$key], (array) $content);
            }
        }

        throw new RuntimeException(sprintf('Cannot create resource by URI "%s"', $uri));
    }
}
