<?php

namespace Propel\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user' table.
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
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'uplink.map.UserTableMap';

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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setClassname('Propel\\User');
        $this->setPackage('uplink');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 25, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 128, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', true, 128, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 128, null);
        // validators
        $this->addValidator('username', 'unique', 'propel.validator.UniqueValidator', '', 'Username already exists!');
        $this->addValidator('username', 'required', 'propel.validator.RequiredValidator', '', 'Username is required.');
        $this->addValidator('username', 'minLength', 'propel.validator.MinLengthValidator', '4', 'Username must be at least 4 characters!');
        $this->addValidator('first_name', 'required', 'propel.validator.RequiredValidator', '', 'First name is required.');
        $this->addValidator('last_name', 'required', 'propel.validator.RequiredValidator', '', 'Last name is required.');
        $this->addValidator('password', 'required', 'propel.validator.RequiredValidator', '', 'Password is required.');
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Rights', 'Propel\\Rights', RelationMap::ONE_TO_ONE, array('id' => 'user_id', ), 'CASCADE', null);
    } // buildRelations()

} // UserTableMap
