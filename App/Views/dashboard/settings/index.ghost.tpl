{{ extends('layouts.master') }}

#set[header]
<h1>
    Configuración
    <small>Gestión de las configuraciones generales</small>
</h1>
#end

#set[breadcrumb]
<li class="active">Configuración</li>
#end

#set[content]
<div class="box">
    <div class="box-body">
        <form id="form-settings" class="form-horizontal form-control-label-left row" method="post">
            <div class="col-sm-6">
                <div class="form-group">
                <label for="setting-name-title" class="control-label col-sm-4">
                    Titulo
                </label>
                <div class="col-sm-8">
                    <input id="setting-name-title" type="text" class="form-control" placeholder="Titulo del sitio" name="title" value="{{$settings->title->settingValue}}">
                </div>
            </div>
                <div class="form-group">
                <label for="setting-name-description" class="control-label col-sm-4">
                    Descripción
                </label>
                <div class="col-sm-8">
                    <input id="setting-name-description" type="text" class="form-control" placeholder="Descripción corta" name="description" value="{{$settings->description->settingValue}}">
                </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-email" class="control-label col-sm-4">
                        Email
                    </label>
                    <div class="col-sm-8">
                        <input id="setting-name-email" type="email" class="form-control" placeholder="Correo electrónico del administrador" name="emailAdmin" value="{{$settings->emailAdmin->settingValue}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-url" class="control-label col-sm-4">
                        Url
                    </label>
                    <div class="col-sm-8">
                        <input id="setting-name-url" type="url" class="form-control" placeholder="Dirección del sitio web" name="siteUrl" value="{{$settings->siteUrl->settingValue}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-pagination-number-rows-show-list" class="control-label col-sm-4">
                        Listado de números de registros por pagina
                    </label>
                    <div class="col-sm-8">
                        <select id="setting-name-pagination-number-rows-show-list" class="form-control select2-dynamic" name="paginationNumberRowsShowList[]" multiple>
                            #foreach($settings->paginationNumberRowsShowList->settingValue as $value)
                                <option value="{{$value}}" selected>{{$value}}</option>
                            #endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-pagination-number-rows-default" class="control-label col-sm-4">
                        Numero de registros por pagina por defecto
                    </label>
                    <div class="col-sm-8">
                        <input id="setting-name-pagination-number-rows-default" type="number" class="form-control" placeholder="Numero de registros por pagina por defecto" name="paginationNumberRowsDefault" value="{{$settings->paginationNumberRowsDefault->settingValue}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-pagination-max-number-pages-show" class="control-label col-sm-4">
                        Numero de paginas (Componente)
                    </label>
                    <div class="col-sm-8">
                        <input id="setting-name-pagination-max-number-pages-show" type="number" class="form-control" placeholder="Numero de paginas (Componente)" name="paginationMaxNumberPagesShow" value="{{$settings->paginationMaxNumberPagesShow->settingValue}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="setting-name-profile-default" class="control-label col-sm-4">
                        Perfil por defecto
                    </label>
                    <div class="col-sm-8">
                        <select id="setting-name-profile-default" class="form-control select2-default" name="profileDefault">
                            #foreach($settings->profiles as $profile)
                                <option value="{{$profile->id}}" {{$profile->id == $settings->profileDefault->settingValue ? 'selected' : ''}}>{{$profile->profileName}}</option>
                            #endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-md-6">
                        <button id="button-setting-update" type="submit" class="btn btn-success btn-block">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
#end

#set[scripts]
#end
