{{ extends('layouts.master') }}

#set[header]
<h1>
    GrAvatar
    <small>Gestión de las configuraciones de GrAvatar</small>
</h1>
#end

#set[breadcrumb]
<li>
    <a href="#"><i class="fa fa-cogs"></i> Configuración</a>
</li>
<li class="active">GrAvatar</li>
#end

#set[content]
<div class="box">
    <div class="box-body">
        <form class="form-horizontal form-control-label-left row" method="post">
            <div class="col-md-9 col-lg-7">
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Tamaño
                    </div>
                    <div class="col-md-10">
                        #foreach($gravatar->gravatarSizeValueList as $size)
                        <label class="radio-inline">
                            <input type="radio" name="gravatarSize" value="{{$size}}" {{$gravatar->gravatarSize->settingValue == $size ? 'checked' : ''}}> {{$size}}
                        </label>
                        #endforeach
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Calificación
                    </div>
                    <div class="col-md-10">
                        #foreach($gravatar->gravatarRatingValueList as $rating)
                        <label class="radio-inline">
                            <input type="radio" name="gravatarRating" value="{{$rating}}" {{$gravatar->gravatarRating->settingValue == $rating ? 'checked' : ''}}> {{$rating}}
                        </label>
                        #endforeach
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Imagen
                        <p class="help-block">Lista de imágenes por defecto.</p>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-xs-6 col-sm-3">
                                #foreach($gravatar->gravatarImageValueList as $image)
                                    <label class="radio">
                                        <input type="radio" name="gravatarImage" value="{{$image->value}}" {{$gravatar->gravatarImage->settingValue == $image->value ? 'checked' : ''}}>
                                        <img src="{{$image->url}}" alt="{{$image->value}}"/>
                                    </label>
                                    #if($incrementGravatarImage++ % 3 == 0 && $incrementGravatarImage < $countGravatarImageValue)
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                    #endif
                                #endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <span class="control-label col-xs-10 text-bold">
                            Forzar
                            <span class="help-block">Imagen por defecto.</span>
                        </span>
                        <span class="col-xs-2 checkbox">
                                <input type="checkbox" name="gravatarForceDefault" {{$gravatar->gravatarForceDefault->settingValue == '1' ? 'checked' : ''}}>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-md-3 col-lg-5">
                <div class="row">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-success btn-block">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
#end

#set[scripts]
#end
