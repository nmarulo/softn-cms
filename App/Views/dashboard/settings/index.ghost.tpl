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
        <form id="form-settings" class="form-horizontal form-control-label-left" method="post">
            <div class="form-group">
                <label for="setting-name-title" class="control-label col-md-2">
                    Titulo
                </label>
                <div class="col-md-10">
                    <input id="setting-name-title" type="text" class="form-control" placeholder="Titulo del sitio" name="title" value="{{$settings->title->settingValue}}">
                </div>
            </div>
            <div class="form-group">
                <label for="setting-name-description" class="control-label col-md-2">
                    Descripción
                </label>
                <div class="col-md-10">
                    <input id="setting-name-description" type="text" class="form-control" placeholder="Descripción corta" name="description" value="{{$settings->description->settingValue}}">
                </div>
            </div>
            <div class="form-group">
                <label for="setting-name-email" class="control-label col-md-2">
                    Email
                </label>
                <div class="col-md-10">
                    <input id="setting-name-email" type="email" class="form-control" placeholder="Correo electrónico del administrador" name="emailAdmin" value="{{$settings->emailAdmin->settingValue}}">
                </div>
            </div>
            <div class="form-group">
                <label for="setting-name-url" class="control-label col-md-2">
                    Url
                </label>
                <div class="col-md-10">
                    <input id="setting-name-url" type="url" class="form-control" placeholder="Dirección del sitio web" name="siteUrl" value="{{$settings->siteUrl->settingValue}}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button id="button-setting-update" type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
#end

#set[scripts]
#end
