<?php
 
Route::group(['namespace' => 'Readerstacks\Drive\Http\controllers'], function(){
    
   
	//Filemanger

	Route::get('migrations/{status}', 'FileManagerUserController@getAllUserForEmail')->name('file-manager.getAllUserForEmail');
 
  
});