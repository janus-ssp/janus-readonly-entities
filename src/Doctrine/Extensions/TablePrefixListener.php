<?php

namespace Janus\Component\ReadonlyEntities\Doctrine\Extensions;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Prefixes the table name with a value from config
 *
 * Based on example from: http://docs.doctrine-project.org/en/latest/cookbook/sql-table-prefixes.html
 */
class TablePrefixListener
{
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $classMetadata->setPrimaryTable(array(
            'name' => $this->prefix . $classMetadata->getTableName()
        ));
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] !== \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                continue;
            }

            $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
            $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
        }
    }
}
