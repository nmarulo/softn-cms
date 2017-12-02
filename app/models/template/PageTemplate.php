<?php
/**
 * PageTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\models\managers\PagesManager;
use SoftnCMS\models\tables\Page;
use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Logger;

/**
 * Class PageTemplate
 * @author Nicolás Marulanda P.
 */
class PageTemplate extends TemplateAbstract {
    
    /** @var Page */
    private $page;
    
    /**
     * PageTemplate constructor.
     *
     * @param Page        $page
     * @param bool        $initRelationShip
     * @param string      $siteUrl
     * @param DBInterface $connectionDB
     */
    public function __construct(Page $page = NULL, $initRelationShip = FALSE, $siteUrl = '', DBInterface $connectionDB = NULL) {
        parent::__construct($siteUrl, $connectionDB);
        $page->setPageContents(Escape::htmlDecode($page->getPageContents()));
        $this->page = $page;
        
        if ($initRelationShip) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
    }
    
    public function initPage($pageId) {
        $pagesManager = new PagesManager($this->getConnectionDB());
        $this->page   = $pagesManager->searchById($pageId);
        
        if ($this->page === FALSE) {
            Logger::getInstance()
                  ->error('La pagina no existe.', ['currentPageId' => $pageId]);
            throw new \Exception(__('La pagina no existe.'));
        }
    }
    
    /**
     * @return Page
     */
    public function getPage() {
        return $this->page;
    }
    
    /**
     * @param Page $page
     */
    public function setPage($page) {
        $this->page = $page;
    }
    
}
