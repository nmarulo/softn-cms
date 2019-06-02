<div class="container-pagination clearfix">
    #if(isset($component_pagination) && $component_pagination->rendered)
    <nav>
        <ul class="pagination pull-right">
            <li class="{{$component_pagination->leftArrow->styleClass}}">
                <a href="#" {{$component_pagination->leftArrow->attrData}}>
                    <span>{{html_entity_decode($component_pagination->leftArrow->value)}}</span>
                </a>
            </li>
            #foreach($component_pagination->pages as $page)
            <li class="{{$page->styleClass}}">
                <a href="#" {{$page->attrData}}>{{$page->value}}</a>
            </li>
            #endforeach
            <li class="{{$component_pagination->rightArrow->styleClass}}">
                <a href="#" {{$component_pagination->rightArrow->attrData}}>
                    <span>{{html_entity_decode($component_pagination->rightArrow->value)}}</span>
                </a>
            </li>
        </ul>
    </nav>
    #endif
</div>
