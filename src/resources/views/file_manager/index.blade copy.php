@extends('layouts.dashboard')

@section('content')

<div class="dbody">
    <div class="dbody-inner">

        @include('message')
        <div class="dashHead">
            <div class="dashHead-left">
                <h4 class="dashTitle">{{$title}}</h4>
                <!-- <p>Showing 1 to 10 of 150 entries</p> -->
            </div>
            <div class="dashHead-right">
                @include('includes.breadcrum')
            </div>
        </div>

        <div class="dashBoard-tiles">
            <div class="dashBoard-tile">
                <div class="dashBoard-title">
                    <!-- <h4>Input</h4> -->
                </div>
                <div class="dForm">
                    <section ng-app="myFileManagerApp" ng-cloak ng-controller="fileManagerCtrl" class="content filemanager ng-cloak ">
                        <!-- Small boxes (Stat box) -->
                        <div class="box box-primary">
                            <div class="upload-queue" ng-cloak ng-if="lengthObj(uploadQueue)>0">
                                Upload Queue :
                                <div class='queue-ls' ng-repeat="q in uploadQueue">  @{{q.name}} - @{{q.complete}}%</div>
                            </div>
                            <div class="box-header with-border no-padding-h-b row">



                                <div class="col-sm-3 col-lg-2 no-padding-h sidebar-fileMan-col">
                                    <div class="sidebar-file-manager @{{scope=='myfiles'?'side-active':''}}" ng-click="setRepo('myfiles')" >
                                        <i class="fa fa-file " aria-hidden="true"></i>
                                        <button type="button" class="btn btn-info"   data-title="All Files"  >

                                            <span>My Files</span>
                                        </button>
                                    </div>
                                    <div class="sidebar-file-manager @{{scope=='shared'?'side-active':''}}"  ng-click="setRepo('shared')" >
                                        <i class="fa fa-file " aria-hidden="true"></i>
                                        <button type="button" class="btn btn-info"  data-title="Shared"  >

                                            <span>Shared with Me</span>
                                        </button>
                                    </div>
                                    <div class="sidebar-file-manager @{{scope=='trash'?'side-active':''}}" ng-click="setRepo('trash')">
                                        <i class="fa fa-recycle " aria-hidden="true"></i>
                                        <button type="button" class="btn btn-info"    data-title="Trash"  >

                                            <span>Recycle Bin</span>
                                        </button>
                                    </div>
                                    <div class="sidebar-file-manager " >
                                        <div style='font-size: 12px;'>
                                            <p> Storage Capacity : @{{storage.storage}}</p>
                                            <p> Storage Used : @{{storage.storage_used}}</p>
                                        </div>

                                    </div>
                                    <div class="sidebar-file-manager " ng-if="deleteundo.activate">
                                        <div ng-click='restoreDeleteUndo()' style="border-radius: 10px;
                                        background: black;
                                        color: white;
                                        cursor: pointer;
                                        padding: 10px;
                                        font-size: 12px;" >
                                        <p>Undo Last deleted file(s)</p>
                                    </div>

                                </div>


                            </div>
                            <div class="col-sm-9 col-lg-10 no-padding-h fmActions">
                                <div class="row">
                                    <div class=" @{{lengthObj(infowindow)>0?'col-md-8':'col-md-12'}}">
                                        <div class="toolview">
                                            <div class="form-search">
                                                <input type="text" ng-keyup="refresh()" ng-model="search_keyword" placeholder="Search for document"/>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A1A1A1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>

                                            <div class="toolview-option">
                                                <div data-tab="tContent1" class="toolview-action active">
                                                    <a href="javascript:;">
                                                        <svg enable-background="new 0 0 512 512" height="20" viewBox="0 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg"><g fill="#5e5e5e"><path d="m176.792 0h-117.584c-32.647 0-59.208 26.561-59.208 59.208v117.584c0 32.647 26.561 59.208 59.208 59.208h117.584c32.647 0 59.208-26.561 59.208-59.208v-117.584c0-32.647-26.561-59.208-59.208-59.208zm19.208 176.792c0 10.591-8.617 19.208-19.208 19.208h-117.584c-10.591 0-19.208-8.617-19.208-19.208v-117.584c0-10.591 8.617-19.208 19.208-19.208h117.584c10.591 0 19.208 8.617 19.208 19.208z" xmlns="http://www.w3.org/2000/svg"/><path d="m452 0h-116c-33.084 0-60 26.916-60 60v116c0 33.084 26.916 60 60 60h116c33.084 0 60-26.916 60-60v-116c0-33.084-26.916-60-60-60zm20 176c0 11.028-8.972 20-20 20h-116c-11.028 0-20-8.972-20-20v-116c0-11.028 8.972-20 20-20h116c11.028 0 20 8.972 20 20z" xmlns="http://www.w3.org/2000/svg"/><path d="m176.792 276h-117.584c-32.647 0-59.208 26.561-59.208 59.208v117.584c0 32.647 26.561 59.208 59.208 59.208h117.584c32.647 0 59.208-26.561 59.208-59.208v-117.584c0-32.647-26.561-59.208-59.208-59.208zm19.208 176.792c0 10.591-8.617 19.208-19.208 19.208h-117.584c-10.591 0-19.208-8.617-19.208-19.208v-117.584c0-10.591 8.617-19.208 19.208-19.208h117.584c10.591 0 19.208 8.617 19.208 19.208z" xmlns="http://www.w3.org/2000/svg"/><path d="m452 276h-116c-33.084 0-60 26.916-60 60v116c0 33.084 26.916 60 60 60h116c33.084 0 60-26.916 60-60v-116c0-33.084-26.916-60-60-60zm20 176c0 11.028-8.972 20-20 20h-116c-11.028 0-20-8.972-20-20v-116c0-11.028 8.972-20 20-20h116c11.028 0 20 8.972 20 20z" xmlns="http://www.w3.org/2000/svg"/></g></svg>
                                                    </a>
                                                </div>

                                                <div data-tab="tContent2" class="toolview-action">
                                                    <a href="javascript:;">
                                                        <svg enable-background="new 0 0 394.971 394.971" height="20" viewBox="0 0 394.971 394.971" width="20" xmlns="http://www.w3.org/2000/svg"><g fill="#5e5e5e"><path d="m56.424 146.286c-28.277 0-51.2 22.923-51.2 51.2s22.923 51.2 51.2 51.2 51.2-22.923 51.2-51.2-22.923-51.2-51.2-51.2zm0 81.502c-16.735 0-30.302-13.567-30.302-30.302s13.567-30.302 30.302-30.302 30.302 13.567 30.302 30.302-13.566 30.302-30.302 30.302z"></path><path d="m379.298 187.037h-236.147c-5.771 0-10.449 4.678-10.449 10.449s4.678 10.449 10.449 10.449h236.147c5.771 0 10.449-4.678 10.449-10.449s-4.678-10.449-10.449-10.449z"></path><path d="m56.424 0c-28.277 0-51.2 22.923-51.2 51.2s22.923 51.2 51.2 51.2 51.2-22.923 51.2-51.2-22.923-51.2-51.2-51.2zm0 81.502c-16.735 0-30.302-13.567-30.302-30.302s13.567-30.302 30.302-30.302 30.302 13.567 30.302 30.302-13.566 30.302-30.302 30.302z"></path><path d="m143.151 61.649h236.147c5.771 0 10.449-4.678 10.449-10.449s-4.678-10.449-10.449-10.449h-236.147c-5.771 0-10.449 4.678-10.449 10.449s4.678 10.449 10.449 10.449z"></path><path d="m56.424 292.571c-28.277 0-51.2 22.923-51.2 51.2s22.923 51.2 51.2 51.2 51.2-22.923 51.2-51.2-22.923-51.2-51.2-51.2zm30.302 51.2c0 16.735-13.567 30.302-30.302 30.302-16.735 0-30.302-13.567-30.302-30.302s13.567-30.302 30.302-30.302 30.302 13.567 30.302 30.302z"></path><path d="m379.298 333.322h-236.147c-5.771 0-10.449 4.678-10.449 10.449s4.678 10.449 10.449 10.449h236.147c5.771 0 10.449-4.678 10.449-10.449s-4.678-10.449-10.449-10.449z"></path></g></svg>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="actions-view">
                                            <button style='color: white;
                                            background: #d00000;' class="btn btn-danger" data-title="Empty Recycle Bin" ng-disabled="   structure.length<=0"  ng-if=" scope=='trash'"  ng-click="emptyTrashCan()">
                                            <i class="fa fa-trash-alt " title="Restore" aria-hidden="true"></i> Empty Recycle Bin
                                        </button>
                                        <button class="btn btn-primary" data-title="Restore Selected"  ng-if=" scope=='trash'"  ng-click="restoreSelected()">
                                            <i class="fa fa-undo " title="Restore" aria-hidden="true"></i>
                                        </button>


                                        <button data-title="Add New Folder" class="btn btn-primary"  ng-disabled="   scope=='trash'"  ng-click="addFolder()">
                                            <i class="fa fa-folder " title="Add Folder" aria-hidden="true"></i>
                                        </button>

                                        <button type="button" class="btn btn-primary" ng-disabled="  scope=='trash'"   data-toggle="modal" data-title="Add New File"  data-target="#myModal">
                                            <i class="fa fa-file " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" ng-disabled="lengthObj(selected)<=0" class="btn btn-danger" ng-click="deleteSelected()"  data-title="Deleted selected"  >
                                            <i class="fa fa-trash " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" ng-disabled="lengthObj(selected)<=0 || scope=='shared' || scope=='trash'" class="btn btn-danger" ng-click="copySelected()" data-title="Copy selected"  >
                                            <i class="fa fa-copy " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" ng-disabled="lengthObj(copied_items)<=0 || scope=='shared' || scope=='trash'" class="btn btn-danger" ng-click="pasteSelected()"  data-title="Paste selected"  >
                                            <i class="fa fa-paste " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-success"  ng-click="refresh()"  data-title="Refresh"  >
                                            <i class="fa fa-redo-alt " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-info"  data-toggle="modal" title="Share"  ng-click="sharePop()" data-target="#sharePopup"  ng-disabled="lengthObj(selected)<=0   || scope=='trash'"    data-title="Share"  >
                                            <i class="fa fa-share " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-info" ng-disabled="lengthObj(selected)<=0 || scope=='shared' || scope=='trash'"  ng-click="rename()"  data-title="Rename"  >
                                            <i class="fa fa-edit " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-success" ng-disabled="lengthObj(selected)<=0  || scope=='trash'"  ng-click="download()"  data-title="Download"  >
                                            <i class="fa fa-download " aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-info" ng-disabled="lengthObj(selected)<=0  "  ng-click="info()"  data-title="Info"  >
                                            <i class="fa fa-info " aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <div>

                                        <a ng-if="breadcrumb.length>0" href="javascript:;"  class="nav-menu-dms-back"  ng-click="back()">  <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                                        <a class="nav-menu-dms" ng-click="open(bread,$index)"  href="javascript:;" ng-repeat="bread in breadcrumb">@{{bread.name}} <span ng-if="!$last" ><i class="fa fa-angle-double-right " aria-hidden="true"></i></span> </a>
                                    </div>

                                    <div id="tContent1" class="tab-content active">
                                        <div eventccp class="file-manager-container" ng-mousedown="openRightMenu($event)"  >

                                            <p ng-if="structure.length<=0">No files and folders</p>
                                            <div class="row">
                                                <div ng-cloak class="inner-file-container" ng-repeat="file in structure">
                                                    <div ng-if="file.is_file==0" oncontextmenu="return false" class="@{{selected[file.id]?'selected':''}} file_folder"  ng-mousedown="markSelected(file,$event)" ng-dblclick="open(file)" >
                                                        <i class="fa fa-folder foldericon" aria-hidden="true"></i>
                                                        <span> @{{file.name}}
                                                        </div>
                                                        <div  ng-if="file.is_file===1" oncontextmenu="return false" ng-mousedown="markSelected(file,$event)" class="@{{selected[file.id]?'selected':''}} file_folder file_name" ng-dblclick=" viewer(file)" >
                                                            <i class="fa fa-@{{file.meta_data.icon}} fileicon" aria-hidden="true"></i>
                                                            <img src="@{{file.meta_data.icon}}"/>
                                                            <span> @{{file.name}}<span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="tContent2" class="tab-content">
                                                <!-- <div eventccp class="file-manager-container" ng-mousedown="openRightMenu($event)"  > -->
                                                <div eventccp class="" ng-mousedown="openRightMenu($event)"  >

                                                    <p ng-if="structure.length<=0">No files and folders</p>

                                                    <div class="listing">
                                                        <table>
                                                            <tr class="list-row">
                                                                <th><a href="javascript:;" ng-click="sort('name')">Name <i ng-if="sortobj.key=='name'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i> </a></th>
                                                                <th><a href="javascript:;" ng-click="sort('is_file')">Type <i  ng-if="sortobj.key=='is_file'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a></th>
                                                                <th><a href="javascript:;" ng-click="sort('storage_size')">Size <i  ng-if="sortobj.key=='storage_size'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a></th>
                                                                <th>Owner</th>
                                                                <th><a href="javascript:;" ng-click="sort('created_at')">Created <i  ng-if="sortobj.key=='created_at'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a></th>
                                                            </tr>

                                                            <tr class="listing-space"><td></td></tr>

                                                            <tr oncontextmenu="return false" class="list-row @{{selected[file.id]?'selected':''}}"  ng-repeat="file in structure" ng-mousedown="markSelected(file,$event)" ng-dblclick="file.is_file==1?viewer(file):open(file)">
                                                                <td  ng-if="file.is_file==0">
                                                                    <div class="list-file-data">
                                                                        <i class="fa fa-folder foldericon" aria-hidden="true"></i>
                                                                        <span> @{{file.name}}</span>
                                                                    </div>
                                                                </td>
                                                                <td ng-if="file.is_file==1">
                                                                    <div class="list-file-data">
                                                                        <!-- <i class="fa fa-@{{file.meta_data.icon}} fileicon" aria-hidden="true"></i> -->
                                                                        <img src="@{{file.meta_data.icon}}"/>
                                                                        <span> @{{file.name}}<span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @{{file.is_file==1?"FILE":"FOLDER"}}
                                                                </td>
                                                                <td>
                                                                    @{{file.storage_size}} KB
                                                                </td>
                                                                <td>@{{file.user.first_name}}</td>
                                                                <td>
                                                                    @{{file.humanDate}}
                                                                </td>
                                                            </tr>


                                                        </table>
                                                    </div>

                                                   <!--  <div class="row">
                                                        <div class="row_head">
                                                            <div style="width:350px" class="head_sort">
                                                                <a href="javascript:;" ng-click="sort('name')">Name <i ng-if="sortobj.key=='name'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i> </a>

                                                            </div>
                                                            <div class="head_sort">
                                                                <a href="javascript:;" ng-click="sort('is_file')">Type <i  ng-if="sortobj.key=='is_file'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a></div>
                                                                <div class="head_sort">
                                                                    <a href="javascript:;" ng-click="sort('storage_size')">Size <i  ng-if="sortobj.key=='storage_size'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a>
                                                                </div>
                                                                <div class="head_sort">Owner</div>

                                                                <div class="head_sort">
                                                                    <a href="javascript:;" ng-click="sort('created_at')">Created <i  ng-if="sortobj.key=='created_at'" class="fa fa-angle-@{{sortobj.order=='ASC'?'up':'down'}}" aria-hidden="true"></i></a>
                                                                </div>



                                                            </div>
                                                            <div ng-cloak class="inner-file-container" ng-repeat="file in structure">

                                                                <div ng-if="file.is_file==0" oncontextmenu="return false" class="@{{selected[file.id]?'selected':''}} file_folder"  ng-mousedown="markSelected(file,$event)" ng-dblclick="open(file)" >

                                                                    <div style="width:350px" class="head_sort">

                                                                        <i class="fa fa-folder foldericon" aria-hidden="true"></i>
                                                                        <span> @{{file.name}}</span>

                                                                    </div>
                                                                    <div class="head_sort">FOLDER</div>
                                                                    <div class="head_sort">@{{file.storage_size}} KB</div>
                                                                    <div data-title="@{{file.user.email}}" class="head_sort">@{{file.user.first_name}}</div>

                                                                    <div class="head_sort">@{{file.humanDate}}</div>

                                                                </div>
                                                                <div   ng-if="file.is_file===1" oncontextmenu="return false" ng-mousedown="markSelected(file,$event)" class="@{{selected[file.id]?'selected':''}} file_folder file_name" ng-dblclick=" viewer(file)" >


                                                                    <div style="width:350px" class="head_sort">

                                                                        <i class="fa fa-@{{file.meta_data.icon}} fileicon" aria-hidden="true"></i>
                                                                        <img src="@{{file.meta_data.icon}}"/>
                                                                        <span> @{{file.name}}<span>
                                                                        </div>
                                                                        <div class="head_sort">FILE</div>
                                                                        <div class="head_sort">@{{file.storage_size}} KB</div>
                                                                        <div data-title="@{{file.user.email}}" class="head_sort">@{{file.user.first_name}}</div>

                                                                        <div class="head_sort">@{{file.humanDate}}</div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->

                                                <div ng-if="lengthObj(infowindow)>0 " class="col-md-4">

                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Name</span>
                                                                    </div>
                                                                    <div class="col-6 namewrap" >
                                                                        <span>@{{infowindow.file.name}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Size</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.meta_data.size}} in KB</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Extenstion</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.meta_data.extention}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Shared</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.shared_type=='0'?"Not Shared":"Shared via"}} @{{infowindow.file.shared_type=='1'?" Email":''}} @{{infowindow.file.shared_type=='2'?" Link":''}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>File Type</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.meta_data.mime_type}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Full Path</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.virtual_path}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Description</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.description}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <span>Created</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span>@{{infowindow.file.created_at}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal" id="myModal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Create New File</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <input id="file" type="file" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <textarea class="form-control" placeholder="Description(tags, index, meta)" ng-model="file_description"   ></textarea>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" ng-click="addFile()" class="buttons primary" data-dismiss="modal">Add</button>
                                                <button type="button" class="buttons secondary" data-dismiss="modal">Close</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal" id="sharePopup">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Share</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <div class="dForm-group">
                                                    <div class="dForm-control select-control">
                                                        <select ng-change="getLink()" ng-model="share.type">

                                                            <option value="1">Share With Email Address</option>
                                                            <option value="2">Share With Link</option>
                                                            <option ng-if="share.file.shared_type!=0" value="0">Remove Permission</option>
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="dForm-group">

                                                    <input class="dForm-control" ng-if="share.type==1" type="text"ng-keyup="getSuggestions($event)" placeholder="Enter multiple emails separate by comma"  ng-model="share.email" />
                                                    <div class="shosuggestion">
                                                        <div class='can-select' ng-repeat="sug in suggestions" ng-click="onSelectEmail(sug.email)" data-id='@{{sug.id}}' value='@{{sug.email}}'>@{{sug.email}}</div>
                                                    </div>

                                                    <input class="dForm-control" ng-if="share.type==2" type="text" placeholder="Copy Link"   ng-model="share.link" readonly="true" />
                                                </div>

                                                <div class="dForm-group">
                                                    <div class="dForm-control select-control">
                                                        <select ng-if="share.type==1 || share.type==2"   ng-change="getLink()" ng-model="share.permission">
                                                            <option value="1">View</option>
                                                            <option value="2">Edit</option>
                                                            <option value="3">Transfer Ownership</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="multiple-mails">
                                                    <div class="row">
                                                        <div class="col multiple-users" ng-if="share.permissions.length">
                                                            <p>Users :</p>
                                                        </div>
                                                        <div class="col multiple-users-mail">

                                                            <span ng-repeat=" per in share.permissions">@{{per.emails}} <a style="display:inline-block; padding: 0 2px; cursor:pointer;" href="javascript:;" ng-click="removeSharePermission(per)"> <i class="fas fa-times"></i></a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" ng-click="shareFileFolder()" class="buttons primary" data-dismiss="modal">Share</button>
                                                <button type="button" class="buttons secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- <div class="modal" id="infoDetail">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title ">@{{infowindow.file.name}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                            <div class="row">
                            <div class="col-md-6">Name
                        </div>
                        <div class="col-md-6 namewrap" >@{{infowindow.file.name}}
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6">Size
            </div>
            <div class="col-md-6">@{{infowindow.file.meta_data.size}} in KB
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">Extenstion
</div>
<div class="col-md-6">@{{infowindow.file.meta_data.extention}}
</div>
</div>
<div class="row">
<div class="col-md-6">Shared
</div>
<div class="col-md-6">@{{infowindow.file.shared_type=='0'?"Not Shared":"Shared via"}} @{{infowindow.file.shared_type=='1'?" Email":''}} @{{infowindow.file.shared_type=='2'?" Link":''}}
</div>
</div>
<div class="row">
<div class="col-md-6">File Type
</div>
<div class="col-md-6">@{{infowindow.file.meta_data.mime_type}}
</div>
</div>
<div class="row">
<div class="col-md-6">Full Path
</div>
<div class="col-md-6">@{{infowindow.file.virtual_path}}
</div>
</div>
<div class="row">
<div class="col-md-6">Description
</div>
<div class="col-md-6">@{{infowindow.file.description}}
</div>
</div>
<div class="row">
<div class="col-md-6">Created
</div>
<div class="col-md-6">@{{infowindow.file.created_at}}
</div>
</div>
</div>
<div class="modal-footer">
<button type="button"   class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div> -->
<div class="modal" id="fileViewrPopup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">@{{fileviewer.file.name}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <iframe style="width: 100%;
                height: 400px;" title="@{{fileviewer.file.name}}" ng-src="@{{trustSrcurl(fileviewer.office_url)}}"  frameborder="0"></iframe>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">

                <button type="button"   class="buttons secondary" data-dismiss="modal">Close</button>

            </div>

        </div>
    </div>
</div>
<div class="popover-ovelay-fm" style="position:fixed;top:0;width:100%;height:100%" ng-show="popover.showPopover" ng-click="popover.showPopover=false"></div>
<div class="popoverfm" id="popoverfm" style="top:@{{popover.top}}px;left:@{{popover.left}}px;" ng-show="popover.showPopover">
    <div class="popoverinner" ng-click="popover.showPopover=false">
        <button   class="btn"    ng-if=" scope=='trash' && popover.TYPE==1"   ng-click="restoreSelected()">
            <i class="fa fa-undo " title="Restore" aria-hidden="true"></i> Restore
        </button>
        <button ng-if="popover.TYPE==2"  class="btn"  ng-disabled="  scope=='shared' || scope=='trash'"  ng-click="addFolder()">
            <i class="fa fa-folder " title="Add Folder" aria-hidden="true"></i> Add New Folder
        </button>

        <button ng-if="popover.TYPE==2" type="button" class="btn" ng-disabled="  scope=='shared' || scope=='trash'"   data-toggle="modal"   data-target="#myModal">
            <i class="fa fa-file " aria-hidden="true"></i> Add New File
        </button>
        <button ng-if="popover.TYPE==1" type="button" ng-disabled="lengthObj(selected)<=0 || scope=='shared' " class="btn" ng-click="deleteSelected()"    >
            <i class="fa fa-trash " aria-hidden="true"></i> Delete
        </button>
        <button ng-if="popover.TYPE==1" type="button" ng-disabled="lengthObj(selected)<=0 || scope=='shared' || scope=='trash'" class="btn" ng-click="copySelected()"    >
            <i class="fa fa-copy " aria-hidden="true"></i> Copy
        </button>
        <button ng-if="popover.TYPE==2" type="button" ng-disabled="lengthObj(copied_items)<=0 || scope=='shared' || scope=='trash'" class="btn" ng-click="pasteSelected()"    >
            <i class="fa fa-paste " aria-hidden="true"></i> Paste
        </button>
        <button  type="button" class="btn"  ng-click="refresh()"     >
            <i class="fas fa-redo"></i> Refresh
        </button>
        <button ng-if="popover.TYPE==1" type="button" class="btn"  data-toggle="modal" title="Share"  ng-click="sharePop()" data-target="#sharePopup"  ng-disabled="lengthObj(selected)<=0 || scope=='shared' || scope=='trash'"      >
            <i class="fa fa-share " aria-hidden="true"></i> Share
        </button>
        <button ng-if="popover.TYPE==1" type="button" class="btn" ng-disabled="lengthObj(selected)<=0 || scope=='shared' || scope=='trash'"  ng-click="rename()"    >
            <i class="fa fa-edit " aria-hidden="true"></i> Rename
        </button>
        <button ng-if="popover.TYPE==1" type="button" class="btn" ng-disabled="lengthObj(selected)<=0  || scope=='trash'"  ng-click="download()"    >
            <i class="fa fa-download " aria-hidden="true"></i> Download
        </button>
        <button ng-if="popover.TYPE==1" type="button" class="btn" ng-disabled="lengthObj(selected)<=0  "  ng-click="info()"   >
            <i class="fa fa-info " aria-hidden="true"></i> Properties
        </button>
    </div>

</div>
<div class="toast " ng-show="showtoast">
    @{{showtoast}}
</div>
</section>

<style type="text/css">
#closed_orders_datatables_filter{
    text-align:right;
}
</style>

</div>
</div>
</div>
</div>
</div>
@endsection

<!-- Main content -->







@section('uniquepagestyle')
<!-- <link rel="stylesheet" href="{{asset('assets/admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('assets/admin/sweetalert.css')}}"> -->
<style>
.row_head{
    padding:0 20px;
}
.head_sort{
    width:160px;
    float:left
}
.ng-cloak {display:none}
.nav-menu-dms{
    padding: 4px;
    color: #5f5f5f;

    font-weight: 700;

}
.nav-menu-dms span{
    position:relative;
    top:1px;
    left:4px;
}
.nav-menu-dms-back{
    padding: 4px;
    color: #5f5f5f;

    font-weight: 700;
}
.modal-backdrop{
    background-color: rgb(0,0,0,0.4) !important;
}
.modal-title, .namewrap{
    word-break: break-all;
}
.upload-queue{
    width:100%;

}
.upload-queue .queue-ls{
    padding:10px;
    background:black;
    color:white;
}
.btn {
    display: inline;
    position: relative;
}

.popoverinner button {
    width:100%;
    padding: 8px;
    width: 100%;
    font-size: 13px;
    border: none;
    text-align: left;
    border-radius: 0px;
    box-shadow: none !important;
    color: #2C2C2C;
    background-color: #fff;
    border-bottom: 1px solid #ccc;
}

.popoverinner button:hover {
    background-color: #f4f4f4 !important;
    color: #2C2C2C !important;
    border-color: #ccc !important;
}

.popoverinner button:last-child {
    border-bottom: none;
}

.fmActions .btn:hover:after {
    background: #111;
    background: rgba(0, 0, 0, .8);
    border-radius: .5em;
    bottom: 2.65em;
    color: #fff;
    content: attr(data-title);
    display: block;
    left: 1em;
    padding: .3em 1em;
    position: absolute;
    text-shadow: 0 1px 0 #000;
    white-space: nowrap;
    z-index: 9998;
}

.fmActions  .btn:hover:before {
    border: solid;
    border-color: #111 transparent;
    border-color: rgba(0, 0, 0, .8) transparent;
    border-width: .4em .4em 0 .4em;
    bottom: 2.3em;
    content: "";
    display: block;
    left: 2em;
    position: absolute;
    z-index: 99;
}

.sidebar-file-manager button{
    background:transparent;
    color:black;
    border:none
}
.sidebar-file-manager button:hover{
    background-color:transparent !important;
    color: black;
    border:none
}

.filemanager {
    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
    -khtml-user-select: none; /* Konqueror HTML */
    -moz-user-select: none; /* Firefox */
    -ms-user-select: none; /* Internet Explorer/Edge */
    user-select: none;
    position:relative;
}
/*
.foldericon{
color:#c7af03;
font-size:70px
}
.fileicon{
color:#9c9c9a;
font-size:60px
} */

/* .selected {
background-color:#9c9a9a;
} */

/* .shosuggestion{
visibility:visible !important;
top: calc(100% + -2px) !important;
opacity: 1 !important;
} */

.toast {
    background:black;color:white;
    padding:10px;
    position:absolute;
    right:40px;
    bottom:40px;
    top:20px;
    height:40px;
}

.file-manager-container {
    display: flex;
    flex-flow:wrap
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal-bs3patch.min.css" integrity="sha512-pZqJY6O1CmsoNXFQzpxc+DqTjVjZksnizgykkLSjj2FeM/KzZyVpy4E1FtX90FoU2Ka8X/nlo/7ebheSThd4Qg==" crossorigin="anonymous" />
@endsection

@section('uniquepagescript')
<script>

if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1==e[0]&&9==e[1]&&e[2]<1||e[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),+function(t){"use strict";function e(e,o){return this.each(function(){var s=t(this),n=s.data("bs.modal"),a=t.extend({},i.DEFAULTS,s.data(),"object"==typeof e&&e);n||s.data("bs.modal",n=new i(this,a)),"string"==typeof e?n[e](o):a.show&&n.show(o)})}var i=function(e,i){this.options=i,this.$body=t(document.body),this.$element=t(e),this.$dialog=this.$element.find(".modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.fixedContent=".navbar-fixed-top, .navbar-fixed-bottom",this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,t.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};i.VERSION="3.4.1",i.TRANSITION_DURATION=300,i.BACKDROP_TRANSITION_DURATION=150,i.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},i.prototype.toggle=function(t){return this.isShown?this.hide():this.show(t)},i.prototype.show=function(e){var o=this,s=t.Event("show.bs.modal",{relatedTarget:e});this.$element.trigger(s),this.isShown||s.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',t.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.bs.modal",function(){o.$element.one("mouseup.dismiss.bs.modal",function(e){t(e.target).is(o.$element)&&(o.ignoreBackdropClick=!0)})}),this.backdrop(function(){var s=t.support.transition&&o.$element.hasClass("fade");o.$element.parent().length||o.$element.appendTo(o.$body),o.$element.show().scrollTop(0),o.adjustDialog(),s&&o.$element[0].offsetWidth,o.$element.addClass("in"),o.enforceFocus();var n=t.Event("shown.bs.modal",{relatedTarget:e});s?o.$dialog.one("bsTransitionEnd",function(){o.$element.trigger("focus").trigger(n)}).emulateTransitionEnd(i.TRANSITION_DURATION):o.$element.trigger("focus").trigger(n)}))},i.prototype.hide=function(e){e&&e.preventDefault(),e=t.Event("hide.bs.modal"),this.$element.trigger(e),this.isShown&&!e.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),t(document).off("focusin.bs.modal"),this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),this.$dialog.off("mousedown.dismiss.bs.modal"),t.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",t.proxy(this.hideModal,this)).emulateTransitionEnd(i.TRANSITION_DURATION):this.hideModal())},i.prototype.enforceFocus=function(){t(document).off("focusin.bs.modal").on("focusin.bs.modal",t.proxy(function(t){document===t.target||this.$element[0]===t.target||this.$element.has(t.target).length||this.$element.trigger("focus")},this))},i.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",t.proxy(function(t){27==t.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")},i.prototype.resize=function(){this.isShown?t(window).on("resize.bs.modal",t.proxy(this.handleUpdate,this)):t(window).off("resize.bs.modal")},i.prototype.hideModal=function(){var t=this;this.$element.hide(),this.backdrop(function(){t.$body.removeClass("modal-open"),t.resetAdjustments(),t.resetScrollbar(),t.$element.trigger("hidden.bs.modal")})},i.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},i.prototype.backdrop=function(e){var o=this,s=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var n=t.support.transition&&s;if(this.$backdrop=t(document.createElement("div")).addClass("modal-backdrop "+s).appendTo(this.$body),this.$element.on("click.dismiss.bs.modal",t.proxy(function(t){return this.ignoreBackdropClick?void(this.ignoreBackdropClick=!1):void(t.target===t.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide()))},this)),n&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!e)return;n?this.$backdrop.one("bsTransitionEnd",e).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION):e()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var a=function(){o.removeBackdrop(),e&&e()};t.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",a).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION):a()}else e&&e()},i.prototype.handleUpdate=function(){this.adjustDialog()},i.prototype.adjustDialog=function(){var t=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&t?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!t?this.scrollbarWidth:""})},i.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},i.prototype.checkScrollbar=function(){var t=window.innerWidth;if(!t){var e=document.documentElement.getBoundingClientRect();t=e.right-Math.abs(e.left)}this.bodyIsOverflowing=document.body.clientWidth<t,this.scrollbarWidth=this.measureScrollbar()},i.prototype.setScrollbar=function(){var e=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"";var i=this.scrollbarWidth;this.bodyIsOverflowing&&(this.$body.css("padding-right",e+i),t(this.fixedContent).each(function(e,o){var s=o.style.paddingRight,n=t(o).css("padding-right");t(o).data("padding-right",s).css("padding-right",parseFloat(n)+i+"px")}))},i.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad),t(this.fixedContent).each(function(e,i){var o=t(i).data("padding-right");t(i).removeData("padding-right"),i.style.paddingRight=o?o:""})},i.prototype.measureScrollbar=function(){var t=document.createElement("div");t.className="modal-scrollbar-measure",this.$body.append(t);var e=t.offsetWidth-t.clientWidth;return this.$body[0].removeChild(t),e};var o=t.fn.modal;t.fn.modal=e,t.fn.modal.Constructor=i,t.fn.modal.noConflict=function(){return t.fn.modal=o,this},t(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(i){var o=t(this),s=o.attr("href"),n=o.attr("data-target")||s&&s.replace(/.*(?=#[^\s]+$)/,""),a=t(document).find(n),r=a.data("bs.modal")?"toggle":t.extend({remote:!/#/.test(s)&&s},a.data(),o.data());o.is("a")&&i.preventDefault(),a.one("show.bs.modal",function(t){t.isDefaultPrevented()||a.one("hidden.bs.modal",function(){o.is(":visible")&&o.trigger("focus")})}),e.call(a,r,this)})}(jQuery);
</script>
<!-- <script src="{{asset('assets/admin/sweetalert.min.js')}}"></script> -->
<!-- <script src="{{asset('assets/admin/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js" integrity="sha512-7oYXeK0OxTFxndh0erL8FsjGvrl2VMDor6fVqzlLGfwOQQqTbYsGPv4ZZ15QHfSk80doyaM0ZJdvkyDcVO7KFA==" crossorigin="anonymous"></script>

<script type="text/javascript">
var base_url='{{url("admin/file-manager")}}';

var app = angular.module('myFileManagerApp', []);
Object.toparams = function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};
app.controller('fileManagerCtrl', function($scope,$http,$timeout,$sce,$timeout,$location,$window) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $scope.structure=[];
    $scope.parent_id=0;
    $scope.breadcrumb=[];
    $scope.tableCache=[];
    $scope.tableIndex={};
    $scope.file_description="";
    $scope.copied_items={};
    $scope.search_keyword="";
    $scope.showPopover=false;
    $scope.showtoast=false;
    $scope.toast_msg='';
    $scope.share={permission:'2',type:'1'};
    $scope.scope="myfiles";
    $scope.fileviewer={};
    $scope.infowindow={};
    $scope.popover={showPopover:false};
    $scope.uploadQueue={};
    $scope.suggestions=[];
    $scope.storage={};
    $scope.sortobj={key:"created_at",order:"DESC"}
    $scope.deleteundo={activate:false,files:{}};


    $scope.getQueryVariable=function(variable) {
        var query = $window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) == variable) {
                return decodeURIComponent(pair[1]);
            }
        }
        console.log('Query variable %s not found', variable);
    }


    $scope.sort=function(key){
        if($scope.sortobj.key==key)
        {
            $scope.sortobj.order=  $scope.sortobj.order=="ASC"?"DESC":"ASC";
        }
        else{
            $scope.sortobj.key=key;
            $scope.sortobj.order="DESC";
        }
        $scope.refresh();
    }
    $scope.lengthObj=function(obj){

        return Object.keys(obj).length
    }

    $scope.setRepo=function(type){
        $scope.scope=type;
        $scope.parent_id=0;
        $scope.search_keyword='';
        $scope.selected={};
        $scope.breadcrumb=[];
        $scope.refresh();
    }
    $scope.toastQueue=[];
    $scope.toast=function(msg,time=2000){
        if($scope.showtoast)
        return  $scope.toastQueue.push({msg:msg,time:time});

        $scope.showtoast=msg;
        $timeout(function(){
            $scope.showtoast=false;
            if($scope.toastQueue.length>0){
                var last=$scope.toastQueue[0];
                $scope.toastQueue.pop();
                $scope.toast(last.msg,last.time);

            }
        },time)

    }


    $scope.getTrash=function(){
        $scope.toast("Loading trash can...",500);
        $http({
            method: 'POST',
            url: base_url+"/trash-can",
            data: Object.toparams( { 'parent_id' : $scope.parent_id }),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })

        .then(function(response) {
            $scope.structure=response.data.list

        });
    }
    $scope.getFileExtensionByTitle = filename => {
        return !!filename
        ? filename
        .split(".")
        .pop()
        .toLowerCase()
        : null;
    }

    $scope.info=function(){
        var sharefolder=$scope.selected[Object.keys($scope.selected)[0]];
        $scope.infowindow.file=sharefolder;
        //$('#infoDetail').modal('toggle');
    }
    $scope.download=function(){
        if(Object.keys($scope.selected).length>1)
        {
            return $scope.toast("Please select only 1 file to download")
        }
        var sharefolder=$scope.selected[Object.keys($scope.selected)[0]];
        if(sharefolder.is_file=='0')
        {
            return $scope.toast("You can only download a file")
        }


        var link = document.createElement("a");
        // If you don't know the name or want to use
        // the webserver default set name = ''
        link.setAttribute('download', sharefolder.name);
        link.href =  "{{url('/')}}/"+sharefolder.path;;
        document.body.appendChild(link);
        link.click();
        link.remove();


    }
    $scope.trustSrcurl = function(data)
    {
        return $sce.trustAsResourceUrl(data);
    }
    $scope.viewer=function(file){
        var fileExtension = $scope.getFileExtensionByTitle(file.name);


        $scope.fileviewer.file=file;
        $('#fileViewrPopup').modal('toggle');
        return     $scope.fileviewer.office_url= `https://docs.google.com/gview?url=${decodeURIComponent(
            "{{url('/')}}/"+file.path+"&embedded=true"
        )}`;
        switch (fileExtension) {
            case "png":
            case "jpg":
            case "jpeg":
            case "gif":
            case "pdf":
            $scope.fileviewer.office_url= "{{url('/')}}/"+file.path;
            break;
            case "ppt":
            case "pptx":
            case "doc":
            case "docx":
            case "xls":
            case "xlsx":
            case "csv":
            $scope.fileviewer.office_url= `https://view.officeapps.live.com/op/embed.aspx?src=${decodeURIComponent(
                "{{url('/')}}/"+file.path
            )}`;
            break;
            default:
            $scope.fileviewer.office_url= "not supported";

        }

        $('#fileViewrPopup').modal('toggle');
    }
    $scope.init=function(){
        $scope.toast("Loading...",500);
        $http({
            method: 'POST',
            url: base_url+"/init",
            data: Object.toparams( { 'parent_id' : $scope.parent_id,scope:$scope.scope,sort:$scope.sortobj.key,odr:$scope.sortobj.order }),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })

        .then(function(response) {
            $scope.structure=response.data.list
            $scope.storage=response.data.storage

        });
    }
    $scope.back=function(){
        $scope.selected={};
        $scope.breadcrumb.splice(-1,1);
        console.log($scope.breadcrumb,"$scope.breadcrumb")
        if($scope.breadcrumb.length >0){
            $scope.parent_id= $scope.breadcrumb[$scope.breadcrumb.length - 1].parent_id;

            $http({
                method: 'POST',
                url: base_url+"/init",
                data: Object.toparams({ 'parent_id' :  $scope.breadcrumb[$scope.breadcrumb.length - 1].id,scope:$scope.scope,sort:$scope.sortobj.key,odr:$scope.sortobj.order }),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .then(function(response) {
                $scope.structure=response.data.list

            });
        }
        else{
            $scope.parent_id=0
            $scope.init();
        }
    }


    $scope.open=function(file,index=-1){
        if($scope.scope=="trash")
        return 1;
        $scope.selected={};
        $scope.parent_id=file.id;
        if(index!=-1){
            console.log( ( $scope.breadcrumb.length-1)-index);
            $scope.breadcrumb.splice(index+1)
        }
        else
        $scope.breadcrumb.push(file)
        $http({
            method: 'POST',
            url: base_url+"/init",
            data: Object.toparams({ 'parent_id' : file.id,scope:$scope.scope ,sort:$scope.sortobj.key,odr:$scope.sortobj.order}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function(response) {
            $scope.structure=response.data.list

        });
    }
    $scope.refresh =function(){
        $scope.toast("Refreshing...",500);
        $http({
            method: 'POST',
            url: base_url+"/init",
            data: Object.toparams({ 'parent_id' : $scope.parent_id,keyword:$scope.search_keyword,scope:$scope.scope,sort:$scope.sortobj.key,odr:$scope.sortobj.order}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function(response) {
            $scope.structure=response.data.list
            if($scope.deeplink && $scope.deeplink.id){
                for(var fl of  $scope.structure){
                    if(fl.id==$scope.deeplink.id){
                        $scope.selected[$scope.deeplink.id]=fl;
                        $scope.deeplink={};
                        break;
                    }

                }
            }
            $scope.storage=response.data.storage
        });
    }
    $scope.addFolder=function(){
        var foldername=prompt("Enter name of folder");
        if(foldername){
            $http({
                method: 'POST',
                url: base_url+"/create-folder",
                data:Object.toparams( { 'parent_id' : $scope.parent_id,name:foldername } ) ,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function(response) {
                if(response.data.status==false){
                    return $scope.toast("Error : " +response.data.message)
                }

                $scope.refresh ()
            });
        }
    }

    $scope.addFile=function(){
        if(document.getElementById('file').value==""){
            alert("No files selected, skipped")
        }
        var formData = new FormData();
        formData.append('file', document.getElementById('file').files[0]);
        formData.append('parent_id',  $scope.parent_id);
        formData.append('description', $scope.file_description );
        var queueNo=Object.keys($scope.uploadQueue).length+1;
        $scope.uploadQueue[queueNo]={};
        $scope.uploadQueue[queueNo]={complete:0,name:document.getElementById('file').files[0].name};

        $http({
            url: base_url+"/create-file",
            method: "POST",
            data: formData,
            headers: {'Content-Type': undefined},
            uploadEventHandlers: {
                progress: function(e){
                    if (e.lengthComputable) {
                        var progressBar = (e.loaded / e.total) * 100;
                        $scope.uploadQueue[queueNo].complete=parseInt(progressBar);
                        //here you will get progress, you can use this to show progress
                    }
                },
            }
        }).then(function (response) {
            delete $scope.uploadQueue[queueNo];

            if(response.data.status==false){
                return $scope.toast("Error : " +response.data.message)
            }

            $scope.file_description=""
            document.getElementById('file').value=""
            $scope.refresh ()
        });

    }
    $scope.activateDeleteUndo=function(files){
        $scope.deleteundo={activate:true,files:files};

    }

    $scope.restoreDeleteUndo=function(){
        $scope.selected=$scope.deleteundo.files;

        $scope.restoreSelected();

        $scope.deleteundo={activate:false,files:{}};

    }
    $scope.deleteSelected=function(){
        $scope.confirmBox({
            msg:"Are you sure you want to move file(s) or folder(s) to Recycle bin?",
            onOk:function(){

                $scope.deleteRequest();
            }
        })


    }

    $scope.deleteRequest=function(){
        $http({
                    method: 'POST',
                    url: base_url+"/delete",
                    data:Object.toparams( { 'selected' : JSON.stringify($scope.selected),scope:$scope.scope } ) ,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(response) {
                    if(response.data.status==false){
                        return $scope.toast("Error : " +response.data.message)
                    }

                    $scope.refresh();
                    $scope.activateDeleteUndo(JSON.parse(JSON.stringify($scope.selected)));
                    $scope.selected={};

                    $scope.toast(response.data.message)


                });
    }

    $scope.confirmBox=function(obj){

        swal({
            title: obj.title?obj.title:"Confirm",
            text: obj.msg?obj.msg:"Are you sure?",
            type: "warning",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn-danger",
            showCancelButton: true,


            cancelButtonText: "No",
            closeOnConfirm: true
        },
        function(){
            if(obj.onOk)
            obj.onOk();
        });
    }
    $scope.emptyTrashCan=function(){
        $scope.confirmBox({
            title:"Confirm Empty",
            msg:'Are you sure you want to permanently delete the file(s) or folder(s)?',
            onOk:function(){

                for(file of $scope.structure){
                    $scope.selected[file.id]=file;
                }
                $scope.deleteRequest();
            }
        }
    )



}
$scope.restoreSelected=function(){

    $http({
        method: 'POST',
        url: base_url+"/restore",
        data:Object.toparams( { 'selected' : JSON.stringify($scope.selected),scope:$scope.scope } ) ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {
        if(response.data.status==false){
            return $scope.toast("Error : " +response.data.message)
        }
        $scope.selected={};
        $scope.refresh ()
    });

}
$scope.copySelected=function(){
    if(Object.keys($scope.selected).length<=0)
    return $scope.toast("Please select items first.")
    $scope.copied_items=JSON.stringify($scope.selected);
    $scope.selected={};
    $scope.toast("Copied selected items successfully.")

}
$scope.pasteSelected=function(){

    $http({
        method: 'POST',
        url: base_url+"/paste",
        data:Object.toparams( { 'selected' : $scope.copied_items,parent_id:$scope.parent_id } ) ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {
        if(response.data.status==false){
            return $scope.toast("Error : " +response.data.message)
        }
        $scope.toast("Item pasted successfully.")
        $scope.copied_items={};

        $scope.refresh ()
    });
}

$scope.getLink=function(){
    var sharefolder=$scope.selected[Object.keys($scope.selected)[0]];
    $scope.share.file_manager_id=Object.keys($scope.selected)[0];
    $scope.share.link="{{url('/')}}/admin/file-manager/shared/"+"shrduip-"+sharefolder.user_id+"-"+sharefolder.id+"-"+$scope.share.permission;
}

$scope.sharePop=function(){
    if(Object.keys($scope.selected).length>1)
    {
        return $scope.toast("Please select only 1 file or folder to share")
    }
    var sharefolder=$scope.selected[Object.keys($scope.selected)[0]];
    $scope.share.file_manager_id=Object.keys($scope.selected)[0];
    $scope.share.file=sharefolder;
    // $scope.share.link="asdKi-"+sharefolder.id+;
    $scope.getLink();
    if(sharefolder.type!="0"){
        $http({
            method: 'POST',
            url: base_url+"/get-shared-users",
            data:Object.toparams( { 'file_manager_id' : $scope.share.file_manager_id} ) ,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response) {
            if(response.data.status==false){
                return $scope.toast("Error : " +response.data.message)
            }
            $scope.share.permissions=response.data.permissions;


        });
    }
}

$scope.removeSharePermission=function(permission){

    $http({
        method: 'POST',
        url: base_url+"/remove-shared-users",
        data:Object.toparams( { 'share_id' : permission.id} ) ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {
        if(response.data.status==false){
            return $scope.toast("Error : " +response.data.message)
        }
        $scope.share.permissions=response.data.permissions;


    });

}

$scope.shareFileFolder=function(){

    $scope.share;
    $http({
        method: 'POST',
        url: base_url+"/share-file",
        data:Object.toparams( $scope.share ) ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {
        if(response.data.status==false){
            return $scope.toast("Error : " +response.data.message)
        }
        $scope.toast("Item shared successfully.")
        $scope.share={permission:'2',type:'1'};
        $scope.selected={}
        $scope.refresh()

    });
}

$scope.rename=function(){

    if(Object.keys($scope.selected).length>1){
        return   alert("Please select one file to rename");
    }
    var keys=Object.keys($scope.selected);
    var name=prompt("Rename the folder or file",$scope.selected[keys[0]].name);
    if(name){
        $http({
            method: 'POST',
            url: base_url+"/rename",
            data:Object.toparams( { 'name' : name,id:$scope.selected[keys[0]].id,is_file:$scope.selected[keys[0]].is_file,parent:$scope.parent_id } ) ,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response) {
            if(response.data.status==false){
                return $scope.toast("Error : " +response.data.message)
            }

            $scope.refresh ()
        });
    }
}
$scope.selected={};
$scope.markSelected=function(file,event){
    event.stopPropagation()
    console.log("right click",event)
    $scope.infowindow={};
    if (event.shiftKey) {
        if($scope.selected[file.id])
        delete $scope.selected[file.id];
        else
        $scope.selected[file.id]=file;

        if(event.which==3) {

            $scope.openRightMenu(event,1)
            return false;
        }
    }
    else{
        $scope.selected={}
        $scope.selected[file.id]=file;
        var sharefolder=$scope.selected[Object.keys($scope.selected)[0]];


        if(event.which==3) {


            $scope.openRightMenu(event,1)

            return false;
        }

    }
}
$scope.openRightMenu=function(e,type){

    if(event.which==3) {

        $scope.popover.top=event.clientY;
        $scope.popover.left=event.clientX;

        $scope.popover.showPopover=true;
        $scope.popover.TYPE=type?type:2;
        $("#popoverfm").height();
        $timeout(function(){
            console.log($("#popoverfm").height(),$(window).height(),$scope.popover.top+$("#popoverfm").height(),$scope.popover.top+$("#popoverfm").height()>$(window).height())
            if($scope.popover.top+$("#popoverfm").height()>$(window).height()){
                $scope.popover.top=$scope.popover.top-($scope.popover.top+$("#popoverfm").height()-$(window).height())-10
            }
        },10)
        return false;
    }
}

$scope.getSuggestions=function(e){

    var splitted=$scope.share.email.split(",");

    $http({
        method: 'POST',
        url: base_url+"/get-share-user-suggestions",
        data:Object.toparams( { 'keyword' :$scope.share.email } ) ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {

        $scope.suggestions=response.data.suggestions;
        console.log($scope.suggestions,response,"$scope.suggestions")
    });
}
$scope.onSelectEmail=function(email){
    var splitted=$scope.share.email.split(",");
    splitted.pop();

    $scope.share.email=splitted.join(", ");

    //   $scope.share.permissions.push({id:0,emails:email});
    $scope.suggestions=[]
}
$scope.deeplink={};
$scope.checkDeepLink=function(){
    var isShare=$scope.getQueryVariable("path");
    if(isShare && isShare=='share'){
        var id=$scope.getQueryVariable("id");
        $scope.deeplink={id:id,repo:'shared'};
        $scope.setRepo("shared");
        return true;
    }
    return false;
}


if(!$scope.checkDeepLink())
$scope.init();

});
app.directive('eventccp', function($window){
    return {
        scope: {},
        link:function(scope,element){
            // $window.on('cut copy paste', function (event) {
            //    alert("aa");
            // });
        }
    };
});
document.addEventListener("contextmenu", function(e){
    e.preventDefault();
}, false);
</script>



@endsection
