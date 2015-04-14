<?php

namespace CmsIr\Menu\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function getRoute() {
        $routeMatch = $this->serviceLocator->getServiceLocator()->get('request')->getUri()->getPath();
        return $routeMatch;
    }

    public function renderMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();

        $template = '<ul class="'.$ulClass.'" id="'.$ulId.'">';

        foreach($menu as $item) {
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();

                $fistItem = end($subItems);
                $checkUrl = $fistItem->getUrl();

                $pos = strpos($route, $checkUrl);
            } else {

                $checkUrl = $item->getItems()->getUrl();
                $pos = strpos($route, $checkUrl);
            }

            if($pos !== false && $checkUrl !== '/') {
                $active = 'active';
            } else {
                if($checkUrl == '/' && strlen($route) == 1)
                {
                    $active = 'active';
                } else {
                    $active = '';
                }
            }


            $template .= '<li class="'.$liClass.  ' ' . $active .'">';



            if(is_array($item->getItems())) {
                $subItems = $item->getItems();

                $fistItem = end($subItems);
                $label = $fistItem->getLabel();
                $url = $fistItem->getUrl();
                array_pop($subItems);


                $template .= '<a href="#">' . $label . '<span class="arrow"></span></a>';



                $template .= '<ul>';

                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();
                    $label = $subItemNode->getLabel();
                    $url = $subItemNode->getUrl();

                    $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                }

                $template .= '</ul>';


            } else {
                $subItem = $item->getItems();

                $label = $subItem->getLabel();
                $url = $subItem->getUrl();
                $template .= '<a href="'.$url.'" class="' . $active . '">'.$label.'<span class="arrow-2"></span></a>';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();

        $template .= '</ul>';
        return $template;
    }

    public function renderSecondMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();

        $menuMain = array_chunk($menu, 3);

        $template = '';


        foreach($menuMain as $menuSmall)
        {

            $template .= '<row>';

            foreach($menuSmall as $small)
            {

                $template .= '<div class="col-sm-4">';

                if(is_array($small->getItems())) {
                    $subItems = $small->getItems();

                    $fistItem = end($subItems);
                    $label = $fistItem->getLabel();
                    $url = $fistItem->getUrl();
                    array_pop($subItems);

                    $template .= '<p><a href="' . $url . '">' . $label . '</a></p>';

                    if(!empty($subItems))
                    {
                        $template .= '<ul>';

                        foreach($subItems as $subItem) {
                            $subItemNode = $subItem->getItems();
                            $label = $subItemNode->getLabel();
                            $url = $subItemNode->getUrl();

                            $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                        }

                        $template .= '</ul>';
                    }

                }

                $template .= '</div>';

            }

            $template .= '</row>';
        }

        return $template;
    }

    public function addExtraOptions()
    {
        $extraFields = '<li class="parent right-icon">
                            <i class="fa fa-phone" id="nav-icon-phone"></i>
                        </li>';
        return $extraFields;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}