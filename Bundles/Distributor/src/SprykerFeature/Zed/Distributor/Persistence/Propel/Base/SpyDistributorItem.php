<?php

namespace SprykerFeature\Zed\Distributor\Persistence\Propel\Base;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Propel;
use Propel\Runtime\Util\PropelDateTime;
use SprykerFeature\Zed\Distributor\Persistence\Propel\Map\SpyDistributorItemTableMap;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemQuery as ChildSpyDistributorItemQuery;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType as ChildSpyDistributorItemType;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemTypeQuery as ChildSpyDistributorItemTypeQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslation;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslationQuery;
use \DateTime;
use \Exception;
use \PDO;

/**
 * Base class that represents a row from the 'spy_distributor_item' table.
 *
 *
 *
 * @package propel.generator.vendor.spryker.spryker.Bundles.Distributor.src.SprykerFeature.Zed.Distributor.Persistence.Propel.Base
 */
abstract class SpyDistributorItem implements ActiveRecordInterface
{

    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SprykerFeature\\Zed\\Distributor\\Persistence\\Propel\\Map\\SpyDistributorItemTableMap';

    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id_distributor_item field.
     * @var int
     */
    protected $id_distributor_item;

    /**
     * The value for the touched field.
     * @var \DateTime
     */
    protected $touched;

    /**
     * The value for the fk_item_type field.
     * @var int
     */
    protected $fk_item_type;

    /**
     * The value for the fk_glossary_translation field.
     * @var int
     */
    protected $fk_glossary_translation;

    /**
     * @var \SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType
     */
    protected $aSpyDistributorItemType;

    /**
     * @var \SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslation
     */
    protected $aSpyGlossaryTranslation;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of SprykerFeature\Zed\Distributor\Persistence\Propel\Base\SpyDistributorItem object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return (bool)$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved. This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute. This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (bool)$b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (bool)$b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>SpyDistributorItem</code> instance. If
     * <code>obj</code> is an instance of <code>SpyDistributorItem</code>, delegates to
     * <code>equals(SpyDistributorItem)</code>. Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\Base\SpyDistributorItem The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, [], true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id_distributor_item] column value.
     *
     * @return int
     */
    public function getIdDistributorItem()
    {
        return $this->id_distributor_item;
    }

    /**
     * Get the [optionally formatted] temporal [touched] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     */
    public function getTouched($format = null)
    {
        if ($format === null) {
            return $this->touched;
        } else {
            return $this->touched instanceof \DateTime ? $this->touched->format($format) : null;
        }
    }

    /**
     * Get the [fk_item_type] column value.
     *
     * @return int
     */
    public function getFkItemType()
    {
        return $this->fk_item_type;
    }

    /**
     * Get the [fk_glossary_translation] column value.
     *
     * @return int
     */
    public function getFkGlossaryTranslation()
    {
        return $this->fk_glossary_translation;
    }

    /**
     * Set the value of [id_distributor_item] column.
     *
     * @param int $v new value
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     */
    public function setIdDistributorItem($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->id_distributor_item !== $v) {
            $this->id_distributor_item = $v;
            $this->modifiedColumns[SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM] = true;
        }

        return $this;
    }

 // setIdDistributorItem()

    /**
     * Sets the value of [touched] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     */
    public function setTouched($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->touched !== null || $dt !== null) {
            if ($this->touched === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->touched->format("Y-m-d H:i:s")) {
                $this->touched = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SpyDistributorItemTableMap::COL_TOUCHED] = true;
            }
        } // if either are not null

        return $this;
    }

 // setTouched()

    /**
     * Set the value of [fk_item_type] column.
     *
     * @param int $v new value
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     */
    public function setFkItemType($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->fk_item_type !== $v) {
            $this->fk_item_type = $v;
            $this->modifiedColumns[SpyDistributorItemTableMap::COL_FK_ITEM_TYPE] = true;
        }

        if ($this->aSpyDistributorItemType !== null && $this->aSpyDistributorItemType->getIdDistributorItemType() !== $v) {
            $this->aSpyDistributorItemType = null;
        }

        return $this;
    }

 // setFkItemType()

    /**
     * Set the value of [fk_glossary_translation] column.
     *
     * @param int $v new value
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     */
    public function setFkGlossaryTranslation($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->fk_glossary_translation !== $v) {
            $this->fk_glossary_translation = $v;
            $this->modifiedColumns[SpyDistributorItemTableMap::COL_FK_GLOSSARY_TRANSLATION] = true;
        }

        if ($this->aSpyGlossaryTranslation !== null && $this->aSpyGlossaryTranslation->getIdGlossaryTranslation() !== $v) {
            $this->aSpyGlossaryTranslation = null;
        }

        return $this;
    }

 // setFkGlossaryTranslation()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    }

 // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows. This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SpyDistributorItemTableMap::translateFieldName('IdDistributorItem', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id_distributor_item = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SpyDistributorItemTableMap::translateFieldName('Touched', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->touched = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SpyDistributorItemTableMap::translateFieldName('FkItemType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fk_item_type = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SpyDistributorItemTableMap::translateFieldName('FkGlossaryTranslation', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fk_glossary_translation = (null !== $col) ? (int)$col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = SpyDistributorItemTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SprykerFeature\\Zed\\Distributor\\Persistence\\Propel\\SpyDistributorItem'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database. It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aSpyDistributorItemType !== null && $this->fk_item_type !== $this->aSpyDistributorItemType->getIdDistributorItemType()) {
            $this->aSpyDistributorItemType = null;
        }
        if ($this->aSpyGlossaryTranslation !== null && $this->fk_glossary_translation !== $this->aSpyGlossaryTranslation->getIdGlossaryTranslation()) {
            $this->aSpyGlossaryTranslation = null;
        }
    }

 // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param \Propel\Runtime\Connection\ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyDistributorItemTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSpyDistributorItemQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSpyDistributorItemType = null;
            $this->aSpyGlossaryTranslation = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see SpyDistributorItem::setDeleted()
     * @see SpyDistributorItem::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyDistributorItemTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSpyDistributorItemQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method. This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyDistributorItemTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SpyDistributorItemTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aSpyDistributorItemType !== null) {
                if ($this->aSpyDistributorItemType->isModified() || $this->aSpyDistributorItemType->isNew()) {
                    $affectedRows += $this->aSpyDistributorItemType->save($con);
                }
                $this->setSpyDistributorItemType($this->aSpyDistributorItemType);
            }

            if ($this->aSpyGlossaryTranslation !== null) {
                if ($this->aSpyGlossaryTranslation->isModified() || $this->aSpyGlossaryTranslation->isNew()) {
                    $affectedRows += $this->aSpyGlossaryTranslation->save($con);
                }
                $this->setSpyGlossaryTranslation($this->aSpyGlossaryTranslation);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

 // doSave()

    /**
     * Insert the row in the database.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM] = true;
        if (null !== $this->id_distributor_item) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM)) {
            $modifiedColumns[':p' . $index++]  = 'id_distributor_item';
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_TOUCHED)) {
            $modifiedColumns[':p' . $index++]  = 'touched';
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_FK_ITEM_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'fk_item_type';
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_FK_GLOSSARY_TRANSLATION)) {
            $modifiedColumns[':p' . $index++]  = 'fk_glossary_translation';
        }

        $sql = sprintf(
            'INSERT INTO spy_distributor_item (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id_distributor_item':
                        $stmt->bindValue($identifier, $this->id_distributor_item, PDO::PARAM_INT);
                        break;
                    case 'touched':
                        $stmt->bindValue($identifier, $this->touched ? $this->touched->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'fk_item_type':
                        $stmt->bindValue($identifier, $this->fk_item_type, PDO::PARAM_INT);
                        break;
                    case 'fk_glossary_translation':
                        $stmt->bindValue($identifier, $this->fk_glossary_translation, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId('spy_distributor_item_pk_seq');
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setIdDistributorItem($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_FIELDNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_FIELDNAME)
    {
        $pos = SpyDistributorItemTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getIdDistributorItem();
                break;
            case 1:
                return $this->getTouched();
                break;
            case 2:
                return $this->getFkItemType();
                break;
            case 3:
                return $this->getFkGlossaryTranslation();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_FIELDNAME.
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_FIELDNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = [], $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['SpyDistributorItem'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SpyDistributorItem'][$this->hashCode()] = true;
        $keys = SpyDistributorItemTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getIdDistributorItem(),
            $keys[1] => $this->getTouched(),
            $keys[2] => $this->getFkItemType(),
            $keys[3] => $this->getFkGlossaryTranslation(),
        ];

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[1]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[1]];
            $result[$keys[1]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSpyDistributorItemType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'spyDistributorItemType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'spy_distributor_item_type';
                        break;
                    default:
                        $key = 'SpyDistributorItemType';
                }

                $result[$key] = $this->aSpyDistributorItemType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSpyGlossaryTranslation) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'spyGlossaryTranslation';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'spy_glossary_translation';
                        break;
                    default:
                        $key = 'SpyGlossaryTranslation';
                }

                $result[$key] = $this->aSpyGlossaryTranslation->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_FIELDNAME.
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem
     */
    public function setByName($name, $value, $type = TableMap::TYPE_FIELDNAME)
    {
        $pos = SpyDistributorItemTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIdDistributorItem($value);
                break;
            case 1:
                $this->setTouched($value);
                break;
            case 2:
                $this->setFkItemType($value);
                break;
            case 3:
                $this->setFkGlossaryTranslation($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST). This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_FIELDNAME.
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_FIELDNAME)
    {
        $keys = SpyDistributorItemTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIdDistributorItem($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTouched($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFkItemType($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setFkGlossaryTranslation($arr[$keys[3]]);
        }
    }

     /**
      * Populate the current object from a string, using a given parser format
      * <code>
      * $book = new Book();
      * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
      * </code>
      *
      * You can specify the key type of the array by additionally passing one
      * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
      * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
      * The default key type is the column's TableMap::TYPE_FIELDNAME.
      *
      * @param mixed $parser A AbstractParser instance,
      *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
      * @param string $data The source data to import from
      * @param string $keyType The type of keys the array uses.
      *
      * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object, for fluid interface
      */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_FIELDNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SpyDistributorItemTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM)) {
            $criteria->add(SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM, $this->id_distributor_item);
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_TOUCHED)) {
            $criteria->add(SpyDistributorItemTableMap::COL_TOUCHED, $this->touched);
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_FK_ITEM_TYPE)) {
            $criteria->add(SpyDistributorItemTableMap::COL_FK_ITEM_TYPE, $this->fk_item_type);
        }
        if ($this->isColumnModified(SpyDistributorItemTableMap::COL_FK_GLOSSARY_TRANSLATION)) {
            $criteria->add(SpyDistributorItemTableMap::COL_FK_GLOSSARY_TRANSLATION, $this->fk_glossary_translation);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws \Propel\Runtime\Exception\LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildSpyDistributorItemQuery::create();
        $criteria->add(SpyDistributorItemTableMap::COL_ID_DISTRIBUTOR_ITEM, $this->id_distributor_item);
        $criteria->add(SpyDistributorItemTableMap::COL_FK_ITEM_TYPE, $this->fk_item_type);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getIdDistributorItem() &&
            null !== $this->getFkItemType();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation spy_distributor_item_fk_d16b97 to table spy_distributor_item_type
        if ($this->aSpyDistributorItemType && $hash = spl_object_hash($this->aSpyDistributorItemType)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = [];
        $pks[0] = $this->getIdDistributorItem();
        $pks[1] = $this->getFkItemType();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setIdDistributorItem($keys[0]);
        $this->setFkItemType($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getIdDistributorItem()) && (null === $this->getFkItemType());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTouched($this->getTouched());
        $copyObj->setFkItemType($this->getFkItemType());
        $copyObj->setFkGlossaryTranslation($this->getFkGlossaryTranslation());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIdDistributorItem(null); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildSpyDistributorItemType object.
     *
     * @param \SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType $v
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setSpyDistributorItemType(ChildSpyDistributorItemType $v = null)
    {
        if ($v === null) {
            $this->setFkItemType(null);
        } else {
            $this->setFkItemType($v->getIdDistributorItemType());
        }

        $this->aSpyDistributorItemType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSpyDistributorItemType object, it will not be re-added.
        if ($v !== null) {
            $v->addSpyDistributorItem($this);
        }

        return $this;
    }


    /**
     * Get the associated ChildSpyDistributorItemType object
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con Optional Connection object.
     * @return \SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType The associated ChildSpyDistributorItemType object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSpyDistributorItemType(ConnectionInterface $con = null)
    {
        if ($this->aSpyDistributorItemType === null && ($this->fk_item_type !== null)) {
            $this->aSpyDistributorItemType = ChildSpyDistributorItemTypeQuery::create()->findPk($this->fk_item_type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSpyDistributorItemType->addSpyDistributorItems($this);
             */
        }

        return $this->aSpyDistributorItemType;
    }

    /**
     * Declares an association between this object and a SpyGlossaryTranslation object.
     *
     * @param \SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslation $v
     * @return $this|\SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setSpyGlossaryTranslation(SpyGlossaryTranslation $v = null)
    {
        if ($v === null) {
            $this->setFkGlossaryTranslation(null);
        } else {
            $this->setFkGlossaryTranslation($v->getIdGlossaryTranslation());
        }

        $this->aSpyGlossaryTranslation = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the SpyGlossaryTranslation object, it will not be re-added.
        if ($v !== null) {
            $v->addSpyDistributorItem($this);
        }

        return $this;
    }


    /**
     * Get the associated SpyGlossaryTranslation object
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con Optional Connection object.
     * @return \SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslation The associated SpyGlossaryTranslation object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSpyGlossaryTranslation(ConnectionInterface $con = null)
    {
        if ($this->aSpyGlossaryTranslation === null && ($this->fk_glossary_translation !== null)) {
            $this->aSpyGlossaryTranslation = SpyGlossaryTranslationQuery::create()->findPk($this->fk_glossary_translation, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSpyGlossaryTranslation->addSpyDistributorItems($this);
             */
        }

        return $this->aSpyGlossaryTranslation;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aSpyDistributorItemType) {
            $this->aSpyDistributorItemType->removeSpyDistributorItem($this);
        }
        if (null !== $this->aSpyGlossaryTranslation) {
            $this->aSpyGlossaryTranslation->removeSpyDistributorItem($this);
        }
        $this->id_distributor_item = null;
        $this->touched = null;
        $this->fk_item_type = null;
        $this->fk_glossary_translation = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aSpyDistributorItemType = null;
        $this->aSpyGlossaryTranslation = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->exportTo(SpyDistributorItemTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
