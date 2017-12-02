<?php
/**
 * MenuController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class MenuController
 * @author Nicolás Marulanda P.
 */
class MenuController extends ControllerAbstract {
    
    public function index() {
        $menusManager = new MenusManager($this->getConnectionDB());
        $count        = $menusManager->count();
        
        $this->sendDataView([
            'menus' => $menusManager->searchAllParent($this->rowsPages($count)),
        ]);
        $this->view('index');
    }
    
    public function create() {
        $menusManager = new MenusManager($this->getConnectionDB());
        $parentMenuId = InputIntegerBuilder::init('parentMenu')
                                           ->setRequire(FALSE)
                                           ->setMethod($_GET)
                                           ->build()
                                           ->filter();
        
        if (empty($parentMenuId)) {
            $this->sendDataView(['parentMenus' => $menusManager->searchAllParent()]);
        } else {
            if (empty($menusManager->searchById($parentMenuId))) {
                Messages::addDanger(__('El menu padre no existe.'), TRUE);
                $this->redirectToAction('index');
            }
            
            $this->sendDataView(['parentMenuId' => $parentMenuId]);
        }
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $menu = $this->getForm('menu');
                
                if ($menusManager->create($menu)) {
                    Messages::addSuccess(__('Menu publicado correctamente.'), TRUE);
                    $parentMenuId = $menu->getMenuSub();
                    
                    if ($parentMenuId != MenusManager::MENU_SUB_PARENT) {
                        $parentMenu = $menusManager->searchParent($parentMenuId);
                        
                        if (!empty($parentMenu)) {
                            $this->redirectToAction('edit/' . $parentMenu->getId());
                        }
                    }
                    
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar el menu.'));
        }
        
        $this->sendDataView([
            'isUpdate' => FALSE,
            'menu'     => new Menu(),
            'title'    => __('Publicar nuevo menu'),
        ]);
        $this->view('form');
    }
    
    public function update($id) {
        $menusManager = new MenusManager($this->getConnectionDB());
        $menu         = $menusManager->searchById($id);
        
        if (empty($menu)) {
            Messages::addDanger(__('El menu no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $menu = $this->getForm('menu');
                
                if ($menusManager->updateByColumnId($menu)) {
                    Messages::addSuccess(__('Menu actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el menu.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos del menu.'));
            }
        }
        
        $parentMenuId = $menu->getMenuSub();
        
        if ($parentMenuId == MenusManager::MENU_SUB_PARENT) {
            $parentMenus = $menusManager->searchAllParent();
            $parentMenus = array_filter($parentMenus, function(Menu $menu) use ($id) {
                return !($menu->getId() == $id);
            });
            $this->sendDataView(['parentMenus' => $parentMenus]);
        } else {
            $this->sendDataView(['parentMenuId' => $parentMenuId]);
            $parentMenu = $menusManager->searchParent($parentMenuId);
            
            if (!empty($parentMenu)) {
                $optionsManager        = new OptionsManager($this->getConnectionDB());
                $siteUrlEditParentMenu = $optionsManager->getSiteUrl() . 'admin/menu/edit/';
                $siteUrlEditParentMenu .= $parentMenu->getId();
                $this->sendDataView(['siteUrlEditParentMenu' => $siteUrlEditParentMenu]);
            }
        }
        
        $this->sendDataView([
            'isUpdate' => TRUE,
            'menu'     => $menu,
            'title'    => __('Actualizar menu'),
        ]);
        $this->view('form');
    }
    
    public function edit($id) {
        $menusManager = new MenusManager($this->getConnectionDB());
        $menu         = $menusManager->searchById($id);
        
        if (empty($menu)) {
            Messages::addDanger(__('El menu no existe.'), TRUE);
            $this->redirectToAction('index');
        }
        
        $this->sendDataView([
            'menusManager' => $menusManager,
            'menu'         => $menu,
            'subMenus'     => $menusManager->searchByMenuSub($id),
        ]);
        $this->view();
    }
    
    public function delete($id) {
        if (Token::check()) {
            $menusManager = new MenusManager($this->getConnectionDB());
            $result       = $menusManager->deleteById($id);
            $rowCount     = $menusManager->getRowCountDelete();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('El menu no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Menu borrado correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar el menu.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $menu = new Menu();
        $menu->setId($this->getInput(MenusManager::COLUMN_ID));
        $menu->setMenuTitle($this->getInput(MenusManager::MENU_TITLE));
        $menu->setMenuUrl($this->getInput(MenusManager::MENU_URL));
        $menu->setMenuSub($this->getInput(MenusManager::MENU_SUB));
        $menu->setMenuPosition(NULL);
        $menu->setMenuTotalChildren(NULL);
        
        return ['menu' => $menu];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(MenusManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(MenusManager::MENU_TITLE)
                                    ->build(),
            InputUrlBuilder::init(MenusManager::MENU_URL)
                           ->setRequire(FALSE)
                           ->build(),
            InputIntegerBuilder::init(MenusManager::MENU_SUB)
                               ->build(),
        ];
    }
    
}
