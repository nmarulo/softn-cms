<div class="container-pagination clearfix">
    #if(isset($component_pagination))
    <nav class="clearfix">
        <div class="select-pagination pull-right">
            <select class="form-control select2-dynamic">
                #foreach($component_pagination->numberRowsShowValueList as $value)
                <option {{$value == $component_pagination->numberRowShow ? 'selected' : ''}}
                        value="{{$value}}">{{$value}}</option>
                #endforeach
            </select>
        </div>
        #if($component_pagination->rendered)
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
        #endif
    </nav>
    #endif
</div>
