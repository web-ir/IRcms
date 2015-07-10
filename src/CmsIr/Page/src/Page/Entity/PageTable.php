<?php

namespace CmsIr\Page\Entity;

use CmsIr\System\Entity\Status;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Join;

class PageTable extends EntityRepository
{
    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row)
        {
            $tmp = array();
            foreach($columns as $column)
            {
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus')
                {
                    $tmp[] = $this->getLabelToDisplay($row->getStatus()->getId(0));
                } elseif($column == 'getStatusId')
                {
                    $tmp[] = $this->getLabelToDisplay($row->getStatus()->getId());
                } else
                {
                    $tmp[] = $row->$column();
                }
            }
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        /* @var $status Status */
        $status = $this->_em->getRepository('CmsIr\System\Entity\Status')->findOneBy(array('id' => $labelValue));

        $status->getName() == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $status->getName() == 'Active' ? $name = 'Aktywna' : $name= 'Nieaktywna';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function getDatatables($columns, $data)
    {
        $displayFlag = false;

        $countAllRows = $this->countRows();

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        if($sorting[0] == 'status_id')
        {
            $sorting[0] = 'status';
        }

        $qb = $this->_em->createQueryBuilder();

        $qb->select('page');
        $qb->orderBy('page.' . $sorting[0], $sorting[1]);
        $qb->setFirstResult($trueOffset);
        $qb->setMaxResults($trueLimit);
        $qb->from('CmsIr\Page\Entity\Page','page');
        $qb->innerJoin('CmsIr\System\Entity\Status', 'status', 'WITH', 'page.status = status.id');

        if ($data->sSearch != '')
        {
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                if(strpos($columns[$i], 'status') === false && strpos($columns[$i], 'groups') === false)
                {
                    $qb->orWhere($qb->expr()->like('page.' . $columns[$i], '?' . $i));
                    $qb->setParameter($i, '%' . $data->sSearch . '%');
                }
            }

            $displayFlag = true;
        }

        $filteredRows = $qb->getQuery()->getResult();

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true)
        {
            $countFilteredRows = count($filteredRows);
        } else
        {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function getSortingColumnDir ($columns, $data)
    {
        for ($i=0 ; $i<intval($data->iSortingCols); $i++)
        {
            if ($data['bSortable_'.intval($data['iSortCol_'.$i])] == 'true')
            {
                $sortingColumn = $columns[$data['iSortCol_'.$i]];
                if(preg_match_all("/[A-Z]/", $sortingColumn, $matches) !== 0)
                {
                    $sortingColumn = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $sortingColumn));
                }
                $sortingDir = $data['sSortDir_'.$i];
                return array($sortingColumn, $sortingDir);
            }
        }
        return array();
    }

    public function countRows()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(page.id)');
        $qb->from('CmsIr\Page\Entity\Page','page');
        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}