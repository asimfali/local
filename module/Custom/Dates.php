<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 19.12.2017
 * Time: 11:23
 */

namespace Custom;


use Zend\Paginator\Adapter\AdapterInterface;

class Dates implements AdapterInterface
{
    protected $vals;
    public function __construct($data)
    {
        $this->vals = $data;
    }

    /**
     * Returns a collection of items for a page.
     *
     * @param  int $offset Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return array_slice($this->vals, $offset, $itemCountPerPage);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->vals);
    }
}