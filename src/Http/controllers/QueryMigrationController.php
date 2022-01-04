<?php

namespace Readerstacks\Drive\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Readerstacks\Drive\Models\FileManager;
use Readerstacks\Drive\Models\FileManagerShare;
use Readerstacks\Drive\Models\FileManagerPermission;
use Readerstacks\Drive\Models\FileManagerUser;
use Session;
Use Config;

class FileManagerController extends Controller
{

    protected $model;
    protected $title;
    protected $pmodule;
    protected $trainer_role_id;
    public function __construct()
    {
        $this->model = 'trainers';
        $this->title = 'File Manager';
        $this->pmodule = 'trainers';
        $this->trainer_role_id = Config::get('constants.options.TRAINER_ROLE_ID');
    } 

    function sessionId($handle=false){
        $id=0;
        if($id==0){
            if(!$handle)
             throw  new \Exception("Please login to access this feature ");
            else
            return $id;
        }
        return $id;
    }

    public function index(){
        return view("Drive::file_manager/index",["title"=>'File Manager']);
    }

    public function trashCan(Request $request)
    {
       
        try
        {   
            $userid= $this->sessionId();
            if($request->keyword!=""){
                $folders=FileManager::where("user_id",$userid)->where(function($q) use($request){
                   return $q->where("name","like","%$request->keyword%")
                    ->orWhere("meta_data","like","%$request->keyword%");

                })->onlyTrashed()->get();
            }
            else
            $folders=FileManager::onlyTrashed()->where("user_id",$userid)->orderBy("deleted_at")->get();
            
            return ["status"=>true,"message"=>"list","list"=>$folders];

        }
        catch(\Exception $e)
        {
            $msg = $e->getMessage();
          
            return ["status"=>false,"message"=>$msg];
        }    

    }
    public function myFiles(Request $request)
    {
        
        try
        {   
            $userid= $this->sessionId($request->scope=='anonymous'?true:false);
               
                $folders=FileManager::select("file_manager.*");

                
                if($request->keyword!=""){
                    $folders=$folders->where(function($q) use($request){
                    return $q->where("name","like","%$request->keyword%")
                        ->orWhere("description","like","%$request->keyword%");

                    });
                }
                if($request->scope=='anonymous'){
                    if($request->parent_id>0){
                        
                        $folders=  \DB::table(\DB::raw("(select * from file_manager where shared_type='2' order by parent_id, id) file_manager, (select @pv := '$request->parent_id') initialisation"))
                          ->whereRaw("find_in_set(parent_id, @pv) > 0 and @pv := concat(@pv, ',', id)") ;
                        // $folders=$folders->where("parent_id",$request->parent_id)->where("shared_type",2);
                    }
                    else
                    $folders=$folders->where("id",base64_decode($request->deep))->where("shared_type",2);
                     

                }
                else if($request->scope=="myfiles"){
                    
                    $folders=$folders->when($request->keyword=="", function ($query) use($request) {
                        
                        return $query->where("parent_id",$request->parent_id);
                    })->where("user_id",$userid);
                    // $folders->when($request->parent_id==0, function ($query) use($request,$userid) {
                        
                    //     return $query;
                    // });

                }
                else if($request->scope=="shared"){
                    $folders= $folders->when($request->keyword=="" && $request->parent_id!=0, function ($query) use($request,$userid) {
                      
                        return $query->where("file_manager_shares.parent_id",$request->parent_id)
                         ->where("file_manager_shares.shared_user_id",$userid)
                        ->leftJoin("file_manager_shares","file_manager_shares.file_manager_id","file_manager.id");;
                    })->when( $request->parent_id==0, function ($query) use($request,$userid) {
                        
                        return $query->where(function($q)use ($userid){
                            return $q->where(["file_manager_shares.shared_user_id"=>$userid,"is_master"=>1]);
                            })
                            // ->leftJoin("file_manager_permissions","file_manager_permissions.file_manager_id","file_manager.id")
                            ->leftJoin("file_manager_shares","file_manager_shares.file_manager_id","file_manager.id");
                    });
                    // $folders->toSql
                }
                else if($request->scope=="trash"){
                   $folders= $folders->where("user_id",$userid)->onlyTrashed()->orderBy("deleted_at");
                }
                
            if($request->sort){
                $folders=$folders->orderBy($request->sort,$request->odr);
            }    
                
            $storage=['hasStoragePermission'=>false,"storage"=>0,"storage_used"=>0];

            if(FileManagerUser::where('user_id',$userid)->count()>0)
            {
                $FileManagerUser=FileManagerUser::where('user_id',$userid)->first();

                $storage['hasStoragePermission']= true;
           
                

                $storage['storage']             = $this->calculateReadableStorage($FileManagerUser->storage); 
                $storage['storage_used']        = $this->calculateReadableStorage($FileManagerUser->storage_used);
                $storage['storage_percent']     = $this->decimalTwo($FileManagerUser->storage_used*100/$FileManagerUser->storage);
                
            
            }
            // $folders=$folders->with("user:id,email");
            return ["status"=>true,"message"=>"list","list"=>$folders->get()
            ,"storage"=>$storage];

        }
        catch(\Exception $e)
        {
            $msg = $e->getMessage();
          
            return ["status"=>false,"message"=>$msg];
        }    

    }
    public function calculateReadableStorage($storage){
        $returnable="0 KB";
       
        if($storage>=1024*1024){
          
            $returnable             = $this->decimalTwo($storage/(1024*1024))." GB"; 
        }
        else if($storage>=1024 ){
            $returnable             = $this->decimalTwo($storage/1024)." MB"; 
             
        }
        else{
            $returnable             = $this->decimalTwo($storage)." KB"; 
        }
        return $returnable;

    }
        public function decimalTwo($val){
        return number_format((float)$val, 2, '.', '');
        } 
    
    public function createFolder(Request $request)
    {
       
       
       \DB::beginTransaction();
        try
        {   
            $userid= $this->sessionId();
            $copy_of=0;
            $verisonnew=1.0;
          
            if(FileManager::where("name",$request->name)->where("parent_id",$request->parent_id)->where("is_file",0)->count()>0){
                //   $version=FileManager::where("name",$request->name)->where("parent_id",$request->parent_id)->where("is_file",0)->first();
                //   if(FileManager::where("copy_of",$version->id)->orderBy("id","DESC")->count()>0){
                //     $version=FileManager::where("copy_of",$version->id)->orderBy("id","DESC")->first();
                      
                //   }
                  
                  
                //   $verisonnew=$version->version+0.1;

                //   $request->name=$request->name."_V".($verisonnew);
                  
                //   $copy_of=$version->id;
                throw  new \Exception("Folder already exist");
                  
            }
            if(!$this->hasPermission($request->parent_id)){
                throw  new \Exception("Permission Denied, You are not permitted for this operation!");
            }
            
            $folderToSaveIn="storage/app/file_manager";
            if($request->parent_id!=0){
                $ownerid=FileManager::find($request->parent_id)->user_id;

            } 
            else{
                $ownerid=$userid;
            }
           
             
                $FileManager=new FileManager;
                $FileManager->name=$request->name;
                $FileManager->path=$folderToSaveIn."/".$request->name;
                $FileManager->parent_id=$request->parent_id;
                $FileManager->user_id=$ownerid;
                $FileManager->version=$verisonnew;
                $FileManager->copy_of=$copy_of;
                $FileManager->virtual_path=$this->getAllParentNode($FileManager);
                
                $FileManager->save(); 
                
                $FileManagerPermission=new FileManagerPermission;
                $FileManagerPermission->user_id         = $ownerid;
                $FileManagerPermission->file_manager_id = $FileManager->id;
                $FileManagerPermission->permission      = 2;
                $FileManagerPermission->save();
                
                
                $this->assignSharePermission($FileManager);

                // $result= \File::makeDirectory( $FileManager->path,0775, true);
                \DB::commit();
            

                return ["status"=>true,"message"=>"Folder created successfully"];
            
             


        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage()."-".$e->getLine();
          
           return ["status"=>false,"message"=>$msg];
        }    

    }

    public function hasPermission($id){
        $userid= $this->sessionId();
        if($id==0){
            return true;
        }
        $owner=FileManager::find($id);
        if($owner->user_id==$userid){
            return true;
        }
        else if($owner->user_id!=$userid){
            $count=FileManagerShare::where("shared_user_id",$userid)->where("file_manager_id",$id)->where("permission",2)->count();
            if($count>0){
                return true;
            }
        }
        return false;

    }

    public function assignSharePermission($FileManager){
        if($FileManager->parent_id==0)
        {
            return null;
        }
        $parent=FileManager::find($FileManager->parent_id);
        if($parent->shared_type==1){
            $FileManager->shared_type      = 1;
            $FileManager->save();
           
            // if($parent->user_id!=$FileManager->user_id){
            //     $FileManagerShare                  = new FileManagerShare;
            //     $FileManagerShare->user_id         = $FileManager->user_id;
            //     $FileManagerShare->shared_user_id  = $parent->user_id;
            //     $FileManagerShare->emails          = 'owner';
            //     $FileManagerShare->parent_id       = $FileManager->parent_id;
            //     $FileManagerShare->is_master       = 0;
            //     $FileManagerShare->file_manager_id = $FileManager->id;
            //     $FileManagerShare->share_type      = $FileManager->shared_type;
            //     $FileManagerShare->permission      = 2;
            //     $FileManagerShare->save();
            // }
            $parentPermissionUsers=FileManagerShare::where("file_manager_id",$FileManager->parent_id)->get();
            foreach($parentPermissionUsers as $parentPermissionUser){
                $FileManagerShare                  = new FileManagerShare;
                $FileManagerShare->user_id         = $parentPermissionUser->user_id;
                $FileManagerShare->shared_user_id  = $parentPermissionUser->shared_user_id;
                $FileManagerShare->emails          = $parentPermissionUser->emails;
                $FileManagerShare->parent_id       = $FileManager->parent_id;
                $FileManagerShare->is_master       = 0;
                $FileManagerShare->file_manager_id = $FileManager->id;
                $FileManagerShare->share_type      = $parentPermissionUser->share_type;
                $FileManagerShare->permission      = $parentPermissionUser->permission;
                $FileManagerShare->save();
            }
        
        }
    }

    public function getAllParentNode($fm,$path="/"){
        if($fm->parent_id==0){
            return "/".$fm->name.$path;

        }
        else {

           return $this->getAllParentNode(FileManager::find($fm->parent_id),"/".$fm->name.$path);
        }

    } 
    public function searchNodeRecursive(){

    }
    public function getIconAccordingToExtension($extension){
        $type="file";
        $extension=strtolower($extension);

        switch($extension){
          
              
            
            case 'docx':
            case 'doc':
                $type='word';
                break;
            case 'xls':
            case 'xlsx':
                $type='ms-excel';
                break;
            case 'csv':
                $type='csv';
                break;
            case 'mp3':
                
            case 'ogg':
            case 'wav':
                $type='music';
                break;
            case 'mp4':
            case 'mov':
                $type='video';
                break;
            case 'zip':
            case '7z':
            case 'rar':
            case 'pdf':
            case 'pdf':
            case 'json':
                case 'xml':
            case 'txt':
            case 'php':
                           
                $type=$extension;
                break;
            case 'jpg':
                 $type='jpg';
                break;
            
            case 'jpeg':
                $type='jpeg';
            break;
            case 'png':
                $type='png';
                break;
            default:
           

                $type='file';
        }
        // if($type=="not-found"){
        //     $res=file_get_contents("https://img.icons8.com/nolan/344/".$extension.".png");
           
        //     try{
        //         echo $res;
        //         json_encode($res);
        //         $type="file";
        //     }
        //     catch(\Exception $e){
        //         $type=$extenstion;
        //     }
        // }
        // dd($type);
        return "https://img.icons8.com/nolan/344/".$type.".png";
    }
    public function createFile(Request $request)
    {
        
        \DB::beginTransaction();
        try
        {   
            $userid= $this->sessionId();
            $folderToSaveIn="/";
            if($request->parent_id!=0){
                // $folderToSaveIn="/".FileManager::find($request->parent_id)->name;

            }
            if(FileManager::where("name",$request->name)->where("parent_id",$request->parent_id)->where("is_file",1)->count()>0){
               
                throw  new \Exception("File name already exist");
                
            }
            if(!$this->hasPermission($request->parent_id)){
                throw  new \Exception("Permission Denied, You are not permitted for this operation!");
            }
            if($request->parent_id!=0){
                $ownerid=FileManager::find($request->parent_id)->user_id;

            } 
            else{
                $ownerid=$userid;
            }
            $document                           = $request->file("file");
            
         
            $FileManager                        = new FileManager;
            $FileManager->name                  = $request->file("file")->getClientOriginalName();
            $FileManager->path                  = "storage/app/".$request->file("file")->store( 'file_manager'); 
            $FileManager->parent_id             = $request->parent_id;
            $FileManager->is_file               = 1;
            $document                           = $request->file("file");
            
            $FileManager->storage_size          =  $document->getSize()/1024;
            
            $FileManager->meta_data             = json_encode(["extention"=>$document->getClientOriginalExtension(),"icon"=>$this->getIconAccordingToExtension($document->getClientOriginalExtension()),"size"=>$document->getSize(),"mime_type"=>$document->getMimeType()]);
            $FileManager->description           =  $request->description;
            $FileManager->virtual_path          =   $this->getAllParentNode($FileManager);
                
            $FileManager->user_id               = $ownerid;
            $FileManager->save(); 
            
            $this->manageStorage($FileManager->user_id,$FileManager->storage_size);
            
            $FileManagerPermission                  = new FileManagerPermission;
            $FileManagerPermission->user_id         = $ownerid;
            $FileManagerPermission->file_manager_id = $FileManager->id;
            $FileManagerPermission->permission      = 2;
            $FileManagerPermission->save();
            $this->assignSharePermission($FileManager);

            
            

            \DB::commit();
            return ["status"=>true,"message"=>"File created successfully","list"=>[]];


        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        }    

    }

    public function pasteItems(Request $request)
    {
        
        $erros=[];
       \DB::beginTransaction();
        try
        {   
            $userid= $this->sessionId();
            $selected= json_decode($request->selected);
            $parentid= $request->parent_id;
            global $erros;
            $erros=[];
            $pasted=[];
            if(count((array)$selected)>0){
                foreach($selected as $id=>$info){
                   
                      if($id==$parentid){
                        throw  new \Exception("Paste in same folder is not allowed");
                      }
                      $this->getFolderFiles($info,function($fileManager,$permission,$parent) use($userid,$request,$pasted){
                        global $erros;
                        if(FileManager::where("name",$fileManager->name)->where("parent_id",$parent)->where("is_file",$fileManager->is_file)->count()>0){
                            $erros[]=$fileManager->name." already exist in destination";
                             
                             return false;
                        }
                      
                
                       
                          $FileManager              = new FileManager;
                          $FileManager->name        = $fileManager->name;
                          $FileManager->path        = $fileManager->path;
                          $FileManager->meta_data   = json_encode($fileManager->meta_data);
                          $FileManager->storage_size=  $fileManager->storage_size;
                          $FileManager->parent_id   = $parent;
                          $FileManager->is_file     = $fileManager->is_file;
                          $FileManager->virtual_path     = $this->getAllParentNode($FileManager);
                          $FileManager->user_id     = $userid;
                          $FileManager->save(); 
                          
                          $this->manageStorage($FileManager->user_id,$FileManager->storage_size);
                           
                          $FileManagerPermission=new FileManagerPermission;
                          $FileManagerPermission->user_id         = $userid;
                          $FileManagerPermission->file_manager_id = $FileManager->id;
                          $FileManagerPermission->permission      = 2;
                          $FileManagerPermission->save();
                          $this->assignSharePermission($FileManager);
                        // if($permission){
                        //     if(FileManagerPermission::where("user_id",$userid)->where("file_manager_id",$permission->file_manager_id)->count()<=0){
                        //         $FileManagerPermission                  = new FileManagerPermission;
                        //         $FileManagerPermission->user_id         = $userid;
                        //         $FileManagerPermission->file_manager_id = $permission->file_manager_id;
                        //         $FileManagerPermission->permission      = $request->permission;
                        //         $FileManagerPermission->save();
                        //     }
                        // }
                         
                       
                        return $FileManager;

                         
                    },$request->parent_id);
                     
                      
                     
                   
      
                   
                    
                }
                // throw  new \Exception("Folder already exist");
                \DB::commit();
            }
             
            return count($erros)>0? ["status"=>false,"message"=>implode(" | ",$erros)]: ["status"=>true,"message"=>"Copied successful"];
            
           

        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg."-".$e->getLine(),"erros"=>$erros,"line"=>$e->getLine(),'$pasted'=>$pasted];
        }    

    }
    function test(){
       
        $i=0;
        $this->getFolderFiles(FileManager::find(1),function($fileManager,$permission) use($i){

           
            if($permission){
                $FileManagerPermission                  = new FileManagerPermission;
                $FileManagerPermission->user_id         = $userid;
                $FileManagerPermission->file_manager_id = $file->file_manager_id;
                $FileManagerPermission->permission      = $file->permission;
                $FileManagerPermission->save();
            }
            if($i==20){
                //die;
            }
            $i++;
        });
 
    }
    public function getFolderFiles($fileRow,$actionToCall,$parent=0,$withTrash=0){
   //   $aman
        $fileManager=$actionToCall($fileRow,FileManagerPermission::where("user_id",$fileRow->user_id)->where("file_manager_id",$fileRow->id)->first(),$parent);
        
        if($fileRow->is_file!='1'){
            $childs=FileManager::where("parent_id",$fileRow->id);
            $childs=$withTrash?$childs->withTrashed()->get():$childs->get();
            foreach( $childs as $child){
                
                $this->getFolderFiles($child,$actionToCall,$fileManager?$fileManager->id:0,$withTrash);
            }
        }
         
    }
   
    public function rename(Request $request){
        
        \DB::beginTransaction();
        try
        { 
            $userid= $this->sessionId();
            if(!$this->hasPermission($request->id)){
                throw  new \Exception("Permission Denied, You are not permitted for this operation!");
            }
            else if(FileManager::where("user_id",$userid)->where("parent_id",$request->parent)->where("id","!=",$request->id)->where("name",$request->name)->where("is_file",$request->is_file)->count()>0){
                throw  new \Exception("Name already exist");
            }
            else{
                $filemanager=FileManager::where("user_id",$userid)->where("id",$request->id)->first();
                $filemanager->name=$request->name;
                $filemanager->save();
                
            }
            \DB::commit();
           return  ["status"=>true,"message"=>"Renamed successfully."];
        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }

    public function restore(Request $request){
        
        \DB::beginTransaction();
        try
        { 
            $userid= $this->sessionId();
            $selected=json_decode($request->selected,true);
           
            if(count($selected)>0  ){
                foreach($selected as $id=>$info){
                   
        
                   
                    FileManager::withTrashed()->find($id)->restore();
      
                }
            }
            \DB::commit();
           return  ["status"=>true,"message"=>"Restored successfully."];
        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }
    
    public function share(Request $request){
        
        \DB::beginTransaction();
        try
        { 
            $userid= $this->sessionId();
            FileManagerPermission::where("file_manager_id",$request->file_manager_id)->first();
            if(!$this->hasPermission($request->file_manager_id)){
                throw  new \Exception("Permission Denied, You are not permitted for this operation!");
            }
            if($request->type=="1"){

                $emails=explode(",",$request->email);
                
                // $FileManager=FileManager::find($request->file_manager_id);
                // $FileManager->shared_type      = 1;
                // $FileManager->save();

                foreach($emails as $email){
                  
                    if(FileManagerUser::where("email",$email)->count()>0){
                        $shareid=FileManagerUser::where("email",$email)->first()->id;
                       
                        $is_master=1;
                        $this->getFolderFiles(FileManager::find($request->file_manager_id),function($fileManager,$permission) use($shareid,$request,&$is_master,$email,$userid){
                           
                            if(FileManagerShare::where(["shared_user_id"=>$shareid,'file_manager_id'=>$fileManager->id])->count()>0){
                                return null;
                            }
                            if($fileManager->parent_id!=0 &&  FileManager::find($fileManager->parent_id)->shared_type==1)
                            {
                                if(FileManagerShare::where("file_manager_id",$fileManager->parent_id)->where("shared_user_id",$shareid)->count()>0)
                                  $is_master=0;
                                 else
                                 $is_master=1;
                                  
                            }
                                $fileManager->shared_type      = 1;
                                $fileManager->save();
                        
                                $FileManagerShare=new FileManagerShare;
                                $FileManagerShare->user_id         = $userid;
                                $FileManagerShare->shared_user_id  = $shareid;
                                $FileManagerShare->emails          = $email;
                                $FileManagerShare->parent_id          = $fileManager->parent_id;
                                $FileManagerShare->is_master          = $is_master;
                                
                                $FileManagerShare->file_manager_id = $fileManager->id;
                                $FileManagerShare->share_type      = 1;
                                $FileManagerShare->permission      = $request->permission;
                                $FileManagerShare->save();
                                $is_master=0;
                            if($permission){
                                if(FileManagerPermission::where("user_id",$shareid)->where("file_manager_id",$permission->file_manager_id)->count()<=0){

                                    // $FileManagerPermission                  = new FileManagerPermission;
                                    // $FileManagerPermission->user_id         = $shareid;
                                    // $FileManagerPermission->file_manager_id = $permission->file_manager_id;
                                    // $FileManagerPermission->permission      = $request->permission;
                                    // $FileManagerPermission->save();
                                     

                                }
                            }
                            
                        });
                       
                       
                    }

                }
                \DB::commit();
                return [
                    "status"=>true,
                    "message"=>"File or Folder shared successfully",

                ];
            }
            else if($request->type=="2"){
                
                // $FileManager=FileManager::find($request->file_manager_id);
                // $FileManager->shared_type      = 2;
                // $FileManager->save();
                $this->getFolderFiles(FileManager::find($request->file_manager_id),function($fileManager,$permission) {
                    
                   
                        $fileManager->shared_type      = 2;
                        $fileManager->save();
                });
                \DB::commit();
                return [
                    "status"=>true,
                    "message"=>"File or Folder shared successfully",

                ];
            }
            else{
                
                $FileManager=FileManager::find($request->file_manager_id);
                $FileManager->shared_type      = 0;
                $FileManager->save();
                 FileManagerShare::where("file_manager_id",$request->file_manager_id)->delete();

                \DB::commit();
                return [
                    "status"=>true,
                    "message"=>"File or Folder shared successfully",

                ];
            }
 
            
        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }
    public function getShareUsers(Request $request){
        
       
        try
        { 
            $userid= $this->sessionId();

                return [
                    "status"=>true,
                    "permissions"=>FileManagerShare::where("file_manager_id",$request->file_manager_id)->get(),

                ];
            
        }
        catch(\Exception $e)
        {
        
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }
    public function removeSharedPermission(Request $request){
        
       
        try
        {   
            $userid= $this->sessionId();
            $permission=FileManagerShare::find($request->share_id);
            if(!$this->hasPermission($permission->file_manager_id)){
                throw  new \Exception("Permission Denied, You are not permitted for this operation!");
            }
            $permission=FileManagerShare::find($request->share_id);
            $permission->delete();
                return [
                    "status"=>true,
                    "permissions"=>FileManagerShare::where("user_id",$userid)->where("file_manager_id",$permission->file_manager_id)->get(),

                ];
            
        }
        catch(\Exception $e)
        {
        
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }
    public function getShareAbleLink(Request $request){
       
        \DB::beginTransaction();
        try
        { 
            $userid= $this->sessionId();
            $selected=json_decode($request->selected,true);
                
            if(count($selected)>0){
                foreach($selected as $id=>$info){
                    if(FileManager::where("id",$id)->where("user_id",$userid)->count()<=0){
                
                        throw new \Exception("You do not have permisions to access this file.");
                    }

                    // $FileManager=FileManager::where("id",$id)->where("user_id",$userid)->first();
                    
                    $link="sKiGYo-$userid-".$id."-".$request->permission;
                     
                }
                \DB::commit();
                return [
                    "status"=>true,
                    "link"=>$link,

                ];
            }
        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }
    public function getShareDetail(Request $request,$shareble_link){
        
        \DB::beginTransaction();
        try
        { 
             
            $userid= $this->sessionId(true);
            $linkpart=explode("-",$shareble_link);
            $hasShared=0;
            if($linkpart[1] && $linkpart[2] && $linkpart[3]){
                $hasShared= FileManager::where("user_id",$linkpart[1])
                ->where("id",$linkpart[2])
                ->where("shared_type","2")->count()>0?1:0;
                
              
            }

           
            if($hasShared>0 && $userid>0){
                $shareid=$userid;
                $email=FileManagerUser::find($userid)->email;
                $is_master=1;
                $this->getFolderFiles(FileManager::find($linkpart[2]),function($fileManager,$permission) use($shareid,$linkpart,&$is_master,$email){
                   
                        if(FileManagerShare::where(["shared_user_id"=>$shareid,'file_manager_id'=>$fileManager->id])->count()>0){
                            return null;
                        }
                        if($fileManager->parent_id!=0 &&  FileManager::find($fileManager->parent_id)->shared_type==2)
                        {
                            if(FileManagerShare::where("file_manager_id",$fileManager->parent_id)->where("shared_user_id",$shareid)->count()>0)
                            $is_master=0;
                            else
                            $is_master=1;
                            
                        }
                        
                        $FileManagerShare=new FileManagerShare;
                        $FileManagerShare->user_id         = $fileManager->user_id;
                        $FileManagerShare->shared_user_id  = $shareid;
                        $FileManagerShare->emails          = $email;
                        $FileManagerShare->parent_id          = $fileManager->parent_id;
                        $FileManagerShare->is_master          = $is_master;
                        
                        $FileManagerShare->file_manager_id = $fileManager->id;
                        $FileManagerShare->share_type      = 2;
                        $FileManagerShare->permission      = $linkpart[3];
                        $FileManagerShare->save();
                        $is_master=0;
                        if($permission){
                            if(FileManagerPermission::where("user_id",$shareid)->where("file_manager_id",$permission->file_manager_id)->count()<=0){

                                // $FileManagerPermission                  = new FileManagerPermission;
                                // $FileManagerPermission->user_id         = $shareid;
                                // $FileManagerPermission->file_manager_id = $permission->file_manager_id;
                                // $FileManagerPermission->permission      = $request->permission;
                                // $FileManagerPermission->save();
                                

                            }
                        }
                    
                });
                // $FileManagerShare=new FileManagerShare;
                // $FileManagerShare->user_id         = $linkpart[1];
                // $FileManagerShare->shared_user_id  = $userid;

                // $FileManagerShare->emails          = \App\Models\User::find($userid)->email;
                
                // $FileManagerShare->file_manager_id = $linkpart[2];
                // $FileManagerShare->share_type      = 2;
                // $FileManagerShare->permission      = $linkpart[3];
                // $FileManagerShare->save();
                // $this->getFolderFiles(FileManager::find($linkpart[2]),function($fileManager,$permission) use($userid,$linkpart){
        
                    
                //     if($permission){
                //         if(FileManagerPermission::where("user_id",$userid)->where("file_manager_id",$permission->file_manager_id)->count()<=0){
                //             $FileManagerPermission                  = new FileManagerPermission;
                //             $FileManagerPermission->user_id         = $userid;
                //             $FileManagerPermission->file_manager_id = $permission->file_manager_id;
                //             $FileManagerPermission->permission      = $linkpart[3];
                //             $FileManagerPermission->save();
                //         }
                //     }
                    
                // });
                \DB::commit();
               
            }
            else if( $hasShared>0 && $userid==0){
                return redirect("/file-manager?path=anonymous&id=".base64_encode($linkpart[2]));

            }
            else{
                throw new \Exception("Invalid Shared Link");
            }

             
            return redirect("/file-manager?path=share&id=".$linkpart[2]);

            
        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage();
          
           return ["status"=>false,"message"=>$msg];
        } 

    }

    public function getUserSuggestions(Request $request){
        
        
        return ["status"=>true,"message"=>'success',"suggestions"=>FileManagerUser::where("email","like","%".$request->keyword."%")->limit(10)->get()];
    }
    public function deleteFileFolder(Request $request)
    {
       
        $msg="Record deleted successfully"; 
        \DB::beginTransaction();
        try
        {   
            $userid= $this->sessionId();
            $folderToSaveIn="";
            $selected=json_decode($request->selected,true);
            
            if(count($selected)>0){
                foreach($selected as $id=>$info){
                   
        
                    
                    

                    if($request->scope=="trash"){
                        if(FileManager::where("id",$id)->where("user_id",$userid)->withTrashed()->count()<=0){
               
                            throw new \Exception("You do not have permisions to delete.");
                        
                        }
                        //$file=FileManager::where("id",$id);
// echo $id;
//                        dd(FileManager::find($id));
                        $this->getFolderFiles(FileManager::withTrashed()->find($id),function($fileManager,$permission) use($request){
                            
                            if($fileManager->is_file=='1'){
                               // echo base_path($fileManager->path);die;
                                if(FileManager::where("path",$fileManager->path)->where("id","!=",$fileManager->id)->withTrashed()->count()<=0){
                                    @unlink(base_path($fileManager->path));
                                }
                                $this->manageStorage($fileManager->user_id,-$fileManager->storage_size);
                                
                                
                            }
                            $fileManager->forceDelete();   
                            if($permission){
                                FileManagerPermission::where("file_manager_id",$permission->file_manager_id)->forceDelete();
                            }
                            
                        },0,1); 
                        $msg="Record deleted permanently"; 
                    }
                    else{
                     
                        if(!$this->hasPermission($id)){
                            throw  new \Exception("Permission Denied, You are not permitted for this operation!");
                        } 
                            $msg="File moved to Recycle bin";
                          FileManager::where("id",$id)->delete();
                    }
                    
                }
            }
            
             
            
            
            

            \DB::commit();
            return ["status"=>true,"message"=>$msg,"list"=>[]];


        }
        catch(\Exception $e)
        {
            \DB::rollback();
           $msg = $e->getMessage()."-".$e->getLine();
          
           return ["status"=>false,"message"=>$msg];
        }    

    }

    function manageStorage($userid,$size){
        if(FileManagerUser::where("user_id",$userid)->count()>0){
            $FileManagerUser =FileManagerUser::where('user_id',$userid)->first();
            $FileManagerUser->storage_used=$FileManagerUser->storage_used+$size;
            if($FileManagerUser->storage_used>$FileManagerUser->storage){
                
                throw new \Exception("Storage out of limit");
            }
            else
             $FileManagerUser->save();

            return true;
        }
        else{
            throw new \Exception("Storage out of limit");
        }
    }
 
}
