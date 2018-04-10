<?php
/**
 * Created by PhpStorm.
 * User: fabio
 * Date: 04/10/16
 * Time: 15.15
 */

namespace Zf\DbUtil;

use Interop\Container\ContainerInterface;
use Zend\Db\Sql\TableIdentifier;
use Zend\ServiceManager\Factory\FactoryInterface;

class TableIdentifierFactory
    implements FactoryInterface
{

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string                                $requestedName
     * @param array|null                            $options
     *
     * @return object|\Zend\Db\Adapter\Adapter
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $Config = $container->get('Config');
        $configDb = $Config['db'];
        if (empty($configDb['table-identifier'][$requestedName])) {
            $path = explode('\\', $requestedName);
            $configTable = [
                'schema' => $path[count($path) - 2],
                'table'  => $path[count($path) - 1],
            ];
            $configDb['table-identifier'][$requestedName] = $configTable;
        }
        $tableIdentifierConfig = $configDb['table-identifier'][$requestedName];
        $object = new TableIdentifier($tableIdentifierConfig['table'], $tableIdentifierConfig['schema']);

        return $object;
    }

}
