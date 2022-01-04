<?php
namespace Readerstacks\QueryMigration\Commands;

use Illuminate\Console\Command;
use Readerstacks\QueryMigration\Models\QueryMigration;

class MigrateCommand extends Command {

    protected $signature = 'QueryMigrate {status}  {--uid= : Run specific id only}  {--f : forecfully run again a migration}';

    protected $description = 'Migrate custom queries';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
         $status=$this->argument('status');
         if(strtolower($status)=="migrate" && $this->option('uid')==NULL){
            $this->info('Starting migrations');
         
            $this->startMigration( );
            $this->info('Completed migrations');
         }  
         else if(strtolower($status)=="migrate" && $this->option('uid')!=NULL){
            $this->info('Starting ONE migrations');
         
            $this->startOneMigration( $this->option('uid'),$this->option('f'));
            $this->info('Completed migrations');
         }   
         else if(strtolower($status)=="pending"){
            $this->info('Pending migrations');
            $this->pendingMigration();
           
         }  
         else{
            $this->error('Command Not Found');
         } 
    }

    public function pendingMigration(){
        
        $queries=config("query_migration_config.queries");
        $pendings=[];

        foreach($queries as $k=>$qr){
            if(!is_array($qr)){
               $pendings[]=$qr;
            }
            else{
                if(!isset($qr["uid"])){
                   
                }
                else{
                    
                    
                    if(QueryMigration::where("uid",$qr["uid"])->count()<=0)
                    {
                         
                          $pendings[]=$qr["query"];
 
                    }
                }
            }
        }
        
        $this->line(($pendings));
        // QueryMigration::where("")->get();

    }

    public function startOneMigration($id=NULL,$force=false){
        
        $queries=config("query_migration_config.queries");
        foreach($queries as $k=>$qr){
            if(!is_array($qr)){
 
                
            }
            else if(isset($qr["uid"]) && $id==$qr["uid"] )
            {
                    
               
                    if(QueryMigration::where("uid",$qr["uid"])->count()<=0  || $force)
                    {
                          
                        try{
                            \DB::statement($qr['query']);
                            
                        }
                        catch(\Exception $e){
                            echo $e->getMessage()  ;
        
                        }
                        
                        $this->line($qr['query']." DONE ");
                    }
                }
                
            
        }
        
         
    }
    public function startMigration(){
        
        $queries=config("query_migration_config.queries");
        foreach($queries as $k=>$qr){
            if(!is_array($qr)){

                $queries[$k]=["query"=>"$qr","name"=>"UNKNOWN","time"=>time(),"uid"=>uniqid()];
                try{
                    \DB::statement($qr);
                }
                catch(\Exception $e){
                    echo $e->getMessage() ." $qr" ;

                }
                $QueryMigration=new QueryMigration;
                $QueryMigration->uid=$queries[$k]["uid"];
                $QueryMigration->name=$queries[$k]["name"];
                $QueryMigration->save();
                $this->line($qr." DONE ");
                
            }
            else{
                if(!isset($qr["uid"])){
                   
                    $queries[$k]=["query"=>$qr["query"],"name"=>"UNKNOWN","time"=>time(),"uid"=>uniqid()];
                    $QueryMigration=new QueryMigration;
                    $QueryMigration->uid=$queries[$k]["uid"];
                    $QueryMigration->name=$queries[$k]["name"];
                    $QueryMigration->save();
                }
                else{
                    
                    
                    if(QueryMigration::where("uid",$qr["uid"])->count()<=0)
                    {
                          
                        try{
                            \DB::statement($qr['query']);
                            
                        }
                        catch(\Exception $e){
                            echo $e->getMessage()  ;
        
                        }
                        $QueryMigration=new QueryMigration;
                        $QueryMigration->uid=$qr["uid"];
                        $QueryMigration->name=$qr["name"];
                        $QueryMigration->save();
                        $this->line($qr['query']." DONE ");
                    }
                }
                
            }
        }
        $query_migration_config=config("query_migration_config");
        $query_migration_config["queries"]=$queries;
        
        file_put_contents(base_path()."/config/query_migration_config.php","<?php return ".var_export($query_migration_config,true).";");
        
        // QueryMigration::where("")->get();

    }
}