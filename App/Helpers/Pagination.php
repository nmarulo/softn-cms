<?php
/**
 * Pagination.php
 */

namespace App\Helpers;

use App\Rest\Response\PageResponse;
use App\Rest\Response\PaginationResponse;

/**
 * Class Pagination
 * @author Nicolás Marulanda P.
 */
class Pagination extends PaginationResponse {
    
    public function getInit(int $totalData, ?int $currentPageValue = NULL, int $maxNumberPagesShow = 3): Pagination {
        $this->currentPageValue   = is_null($currentPageValue) ? 1 : $currentPageValue;
        $this->totalData          = $totalData;
        $this->maxNumberPagesShow = $maxNumberPagesShow;
        $this->pages              = [];
        $this->totalNumberPages   = 0;
        $this->rendered           = FALSE;
        $this->beginRow           = 0;
        //TODO: configurar en base de datos.
        $this->setNumberRowShow(2);
        $this->initPagination();
        
        return $this;
    }
    
    /**
     * @param int $numberRowShow
     */
    private function setNumberRowShow(int $numberRowShow): void {
        if ($numberRowShow <= 0) {
            $numberRowShow = 1;
        }
        
        $this->numberRowShow = $numberRowShow;
    }
    
    private function initPagination(): void {
        //Se comprueba que sea mayor que 0 para evitar error en la operaciones.
        if ($this->totalData > 0) {
            /*
             * Comprueba que el número de datos a mostrar por pagina
             * no sea mayor al total de filas a mostrar, para obtener
             * el numero correcto de filas a mostrar.
             */
            if ($this->numberRowShow > $this->totalData) {
                $this->numberRowShow = $this->totalData;
            }
            
            $this->totalNumberPages = ceil($this->totalData / $this->numberRowShow);
            
            //Se podrá mostrar la paginación si hay mas de una pagina.
            if ($this->totalNumberPages > 1) {
                $this->rendered = TRUE;
                
                //Se comprueba el valor de la pagina actual no es valida.
                if ($this->currentPageValue <= 0) {
                    $this->currentPageValue = 1;
                }
                
                //Se comprueba si la pagina actual es mayor al numero total de paginas
                if ($this->currentPageValue > $this->totalNumberPages) {
                    $this->currentPageValue = $this->totalNumberPages;
                }
                
                //Se establece la posición de inicio de la fila.
                $this->beginRow = ($this->numberRowShow * $this->currentPageValue) - $this->numberRowShow;
                
                $this->initPages();
            }
        }
    }
    
    private function initPages(): void {
        /*
         * Para evitar los casos donde el total de paginas es demasiado grande
         * se establece un maximo de paginas a mostrar ($maxNumberPagesShow)
         */
        
        $startPageNumber = $this->currentPageValue - $this->maxNumberPagesShow;
        $endPageNumber   = $this->currentPageValue + $this->maxNumberPagesShow;
        
        if ($startPageNumber <= 0) {
            /*
             * Cuando la pagina inicial es menor que 0,
             * obtengo su valor positivo y le sumo 1
             * y se le suma el numero de la pagina final,
             * con esto se mostrara siempre el mismo numero de paginas.
             */
            $endPageNumber += (abs($startPageNumber) + 1);
            
            if ($endPageNumber > $this->totalNumberPages) {
                $endPageNumber = $this->totalNumberPages;
            }
            
            $startPageNumber = 1;
        } else {
            if ($endPageNumber > $this->totalNumberPages) {
                /*
                 * Al igual que en la comprobación del numero de la pagina inicial,
                 * para mostrar siempre el mismo numero de paginas,
                 * obtengo el numero de paginas que exceden el total de paginas
                 * y se lo resto al numero de la pagina inicial.
                 */
                $startPageNumber -= ($endPageNumber - $this->totalNumberPages);
                
                if ($startPageNumber <= 0) {
                    $startPageNumber = 1;
                }
                
                $endPageNumber = $this->totalNumberPages;
            }
        }
        
        $this->setPages($startPageNumber, $endPageNumber);
        $this->initArrows();
    }
    
    private function setPages(int $startPageNumber, int $endPageNumber): void {
        $pages = [];
        
        for ($i = $startPageNumber; $i <= $endPageNumber; ++$i) {
            $styleClass = '';
            $attrData   = [
                    'page' => $i,
                    'type' => 'page',
            ];
            
            if ($this->currentPageValue == $i) {
                $styleClass = 'active';
                unset($attrData['page']);
            }
            
            $pages[] = $this->newPage($i, $styleClass, $attrData);
        }
        
        $this->pages = $pages;
    }
    
    private function initArrows(): void {
        $styleClass = "disabled";
        $attrData   = [
                'type' => 'arrow',
        ];
        $this->setLeftArrow($styleClass, $attrData);
        $this->setRightArrow($styleClass, $attrData);
    }
    
    private function setLeftArrow($styleClass, $attrData): void {
        if ($this->currentPageValue > 1) {
            $styleClass       = "";
            $attrData['page'] = $this->currentPageValue - 1;
        }
        
        $this->leftArrow = $this->newPage('&laquo;', $styleClass, $attrData);
    }
    
    private function setRightArrow($styleClass, $attrData): void {
        if ($this->currentPageValue < $this->totalNumberPages) {
            $styleClass       = "";
            $attrData['page'] = $this->currentPageValue + 1;
        }
        
        $this->rightArrow = $this->newPage('&raquo;', $styleClass, $attrData);
    }
    
    private function attrToString(array $attrData): string {
        $attr = array_map(function($key, $value) {
            return "data-${key}='${value}'";
        }, array_keys($attrData), $attrData);
        
        return implode(' ', $attr);
    }
    
    private function newPage(string $value, string $styleClass, array $attrData): PageResponse {
        $page             = new PageResponse();
        $page->value      = $value;
        $page->styleClass = $styleClass;
        $page->attrData   = $this->attrToString($attrData);
        $page->attr       = $attrData;
        
        return $page;
    }
}
