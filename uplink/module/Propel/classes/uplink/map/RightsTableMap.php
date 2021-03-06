<?php

namespace Propel\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'rights' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.uplink.map
 */
class RightsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'uplink.map.RightsTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('rights');
        $this->setPhpName('Rights');
        $this->setClassname('Propel\\Rights');
        $this->setPackage('uplink');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('user_id', 'UserId', 'INTEGER' , 'user', 'id', true, null, null);
        $this->addColumn('unlocked', 'Unlocked', 'BOOLEAN', true, 1, null);
        $this->addColumn('right1', 'Right1', 'BOOLEAN', true, 1, null);
        $this->addColumn('right2', 'Right2', 'BOOLEAN', true, 1, null);
        // validators
        $this->addValidator('unlocked', 'required', 'propel.validator.RequiredValidator', '', 'Unlocked right is required.');
        $this->addValidator('right1', 'required', 'propel.validator.RequiredValidator', '', 'Right1 right is required.');
        $this->addValidator('right2', 'required', 'propel.validator.RequiredValidator', '', 'Right2 right is required.');
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // RightsTableMap
