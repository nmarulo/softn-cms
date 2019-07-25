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
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Tamaño
                    </div>
                    <div class="col-md-10">
                        <label class="radio-inline">
                            <input type="radio" name="gravatarSize" value="32" {{$gravatar->gravatarSize->settingValue == 32 ? 'checked' : ''}}> 32
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarSize" value="64" {{$gravatar->gravatarSize->settingValue == 64 ? 'checked' : ''}}> 64
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarSize" value="128" {{$gravatar->gravatarSize->settingValue == 128 ? 'checked' : ''}}> 128
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarSize" value="256" {{$gravatar->gravatarSize->settingValue == 256 ? 'checked' : ''}}> 256
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Imagen
                        <p class="help-block">Lista de imágenes por defecto.</p>
                    </div>
                    <div class="col-md-10">
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="mm" {{$gravatar->gravatarImage->settingValue == 'mm' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=mm"/>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="blank" {{$gravatar->gravatarImage->settingValue == 'blank' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=blank"/>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="monsterid" {{$gravatar->gravatarImage->settingValue == 'monsterid' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=monsterid"/>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="wavatar" {{$gravatar->gravatarImage->settingValue == 'wavatar' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=wavatar"/>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="retro" {{$gravatar->gravatarImage->settingValue == 'retro' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=retro"/>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarImage" value="identicon" {{$gravatar->gravatarImage->settingValue == 'identicon' ? 'checked' : ''}}>
                            <img src="https://www.gravatar.com/avatar/?d=identicon"/>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Calificación
                    </div>
                    <div class="col-md-10">
                        <label class="radio-inline">
                            <input type="radio" name="gravatarRating" value="g" {{$gravatar->gravatarRating->settingValue == 'g' ? 'checked' : ''}}> g
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarRating" value="pg" {{$gravatar->gravatarRating->settingValue == 'pg' ? 'checked' : ''}}> pg
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarRating" value="r" {{$gravatar->gravatarRating->settingValue == 'r' ? 'checked' : ''}}> r
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gravatarRating" value="x" {{$gravatar->gravatarRating->settingValue == 'x' ? 'checked' : ''}}> x
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-md-2 text-bold">
                        Forzar
                        <p class="help-block">Imagen por defecto.</p>
                    </div>
                    <div class="col-md-10 checkbox">
                        <label>
                            <input type="checkbox" name="gravatarForceDefault" {{$gravatar->gravatarForceDefault->settingValue == '1' ? 'checked' : ''}}>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-md-6">
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
