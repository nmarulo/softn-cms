<div class="pagination-container">
    #if(isset($component_pagination) && $component_pagination->isRendered())
    <nav>
        <ul class="pagination clearfix">
            <li class="{{$component_pagination->getLeftArrow()->getStyleClass()}}">
                <a href="#" {{$component_pagination->getLeftArrow()->getAttrData()}}>
                    <span>{{html_entity_decode($component_pagination->getLeftArrow()->getValue())}}</span>
                </a>
            </li>
            #foreach($component_pagination->getPages() as $page)
            <li class="{{$page->getStyleClass()}}">
                <a href="#" {{$page->getAttrData()}}>{{$page->getValue()}}</a>
            </li>
            #endforeach
            <li class="{{$component_pagination->getRightArrow()->getStyleClass()}}">
                <a href="#" {{$component_pagination->getRightArrow()->getAttrData()}}>
                    <span>{{html_entity_decode($component_pagination->getRightArrow()->getValue())}}</span>
                </a>
            </li>
        </ul>
    </nav>
    #endif
</div>
