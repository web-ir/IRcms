<?php
namespace CmsIr\File\Model;

use CmsIr\System\Model\ModelTable;
use CmsIr\System\Util\Inflector;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GalleryTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus') {
                    $tmp[] = $this->getLabelToDisplay($row->getStatusId());
                } else {
                    $tmp[] = $row->$column();
                }
            }
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Active' ? $name = 'Aktywna' : $name= 'Nieaktywna';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function save(Gallery $gallery)
    {
        $data = array(
            'name' => $gallery->getName(),
            'slug' => Inflector::slugify($gallery->getName()),
            'status_id' => $gallery->getStatusId(),
            'category_id' => $gallery->getCategoryId(),
            'filename_main' => $gallery->getFilenameMain(),
            'position' => $gallery->getPosition(),
        );

        $id = (int) $gallery->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;

            $pos = array('position' => $id);

            $this->tableGateway->update($pos, array('id' => $id));
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Gallery id does not exist');
            }
        }

        return $id;
    }

    public function deleteGallery($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function changeStatusGallery($ids, $statusId)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }
        $data = array('status_id'  => $statusId);
        foreach($ids as $id) {
            $this->tableGateway->update($data, array('id' => $id));
        }
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }
}