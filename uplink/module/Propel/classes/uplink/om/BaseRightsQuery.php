<?php

namespace Propel\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Propel\Rights;
use Propel\RightsPeer;
use Propel\RightsQuery;
use Propel\User;

/**
 * Base class that represents a query for the 'rights' table.
 *
 *
 *
 * @method RightsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method RightsQuery orderByUnlocked($order = Criteria::ASC) Order by the unlocked column
 * @method RightsQuery orderByRight1($order = Criteria::ASC) Order by the right1 column
 * @method RightsQuery orderByRight2($order = Criteria::ASC) Order by the right2 column
 *
 * @method RightsQuery groupByUserId() Group by the user_id column
 * @method RightsQuery groupByUnlocked() Group by the unlocked column
 * @method RightsQuery groupByRight1() Group by the right1 column
 * @method RightsQuery groupByRight2() Group by the right2 column
 *
 * @method RightsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method RightsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method RightsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method RightsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method RightsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method RightsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method Rights findOne(PropelPDO $con = null) Return the first Rights matching the query
 * @method Rights findOneOrCreate(PropelPDO $con = null) Return the first Rights matching the query, or a new Rights object populated from the query conditions when no match is found
 *
 * @method Rights findOneByUnlocked(boolean $unlocked) Return the first Rights filtered by the unlocked column
 * @method Rights findOneByRight1(boolean $right1) Return the first Rights filtered by the right1 column
 * @method Rights findOneByRight2(boolean $right2) Return the first Rights filtered by the right2 column
 *
 * @method array findByUserId(int $user_id) Return Rights objects filtered by the user_id column
 * @method array findByUnlocked(boolean $unlocked) Return Rights objects filtered by the unlocked column
 * @method array findByRight1(boolean $right1) Return Rights objects filtered by the right1 column
 * @method array findByRight2(boolean $right2) Return Rights objects filtered by the right2 column
 *
 * @package    propel.generator.uplink.om
 */
abstract class BaseRightsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseRightsQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'uplink_test', $modelName = 'Propel\\Rights', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RightsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   RightsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return RightsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RightsQuery) {
            return $criteria;
        }
        $query = new RightsQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Rights|Rights[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RightsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(RightsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Rights A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByUserId($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Rights A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `user_id`, `unlocked`, `right1`, `right2` FROM `rights` WHERE `user_id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Rights();
            $obj->hydrate($row);
            RightsPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Rights|Rights[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Rights[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RightsPeer::USER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RightsPeer::USER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id >= 12
     * $query->filterByUserId(array('max' => 12)); // WHERE user_id <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(RightsPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(RightsPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RightsPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the unlocked column
     *
     * Example usage:
     * <code>
     * $query->filterByUnlocked(true); // WHERE unlocked = true
     * $query->filterByUnlocked('yes'); // WHERE unlocked = true
     * </code>
     *
     * @param     boolean|string $unlocked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByUnlocked($unlocked = null, $comparison = null)
    {
        if (is_string($unlocked)) {
            $unlocked = in_array(strtolower($unlocked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(RightsPeer::UNLOCKED, $unlocked, $comparison);
    }

    /**
     * Filter the query on the right1 column
     *
     * Example usage:
     * <code>
     * $query->filterByRight1(true); // WHERE right1 = true
     * $query->filterByRight1('yes'); // WHERE right1 = true
     * </code>
     *
     * @param     boolean|string $right1 The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByRight1($right1 = null, $comparison = null)
    {
        if (is_string($right1)) {
            $right1 = in_array(strtolower($right1), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(RightsPeer::RIGHT1, $right1, $comparison);
    }

    /**
     * Filter the query on the right2 column
     *
     * Example usage:
     * <code>
     * $query->filterByRight2(true); // WHERE right2 = true
     * $query->filterByRight2('yes'); // WHERE right2 = true
     * </code>
     *
     * @param     boolean|string $right2 The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function filterByRight2($right2 = null, $comparison = null)
    {
        if (is_string($right2)) {
            $right2 = in_array(strtolower($right2), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(RightsPeer::RIGHT2, $right2, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 RightsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(RightsPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RightsPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Propel\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Rights $rights Object to remove from the list of results
     *
     * @return RightsQuery The current query, for fluid interface
     */
    public function prune($rights = null)
    {
        if ($rights) {
            $this->addUsingAlias(RightsPeer::USER_ID, $rights->getUserId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
