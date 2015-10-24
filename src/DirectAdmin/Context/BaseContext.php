<?php
/**
 * DirectAdmin
 * (c) Omines Internetbureau B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omines\DirectAdmin\Context;

use Omines\DirectAdmin\DirectAdmin;
use Omines\DirectAdmin\DirectAdminException;

/**
 * Encapsulates a contextual connection to a DirectAdmin server.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com
 */
abstract class BaseContext
{
    /** @var DirectAdmin */
    private $connection;

    /**
     * @param DirectAdmin $connection A prepared connection.
     */
    public function __construct(DirectAdmin $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return DirectAdmin
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->connection->getUsername();
    }

    /**
     * Invokes the DirectAdmin API via HTTP GET.
     *
     * @param string $command DirectAdmin API command to invoke.
     * @param array $query Optional query parameters.
     * @return array The parsed and validated response.
     */
    public function invokeGet($command, $query = [])
    {
        return $this->connection->invoke('GET', $command, ['query' => $query]);
    }

    /**
     * Invokes the DirectAdmin API via HTTP POST.
     *
     * @param string $command DirectAdmin API command to invoke.
     * @param array $postParameters Optional form parameters.
     * @return array The parsed and validated response.
     */
    public function invokePost($command, $postParameters = [])
    {
        return $this->connection->invoke('POST', $command, ['form_params' => $postParameters]);
    }

    /**
     * Throws exception if any of the required options are not set.
     *
     * @param array $options Associative array of options.
     * @param array $required Flat array of required options.
     * @throws DirectAdminException If any of the options were missing.
     */
    protected static function checkMandatoryOptions(array $options, array $required)
    {
        if(!empty($diff = array_diff($required, array_keys($options))))
            throw new DirectAdminException('Missing required options: ' . implode(', ', $diff));
    }
}
