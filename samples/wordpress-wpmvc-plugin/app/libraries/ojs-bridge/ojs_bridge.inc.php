<?php
/*
OJS Bridge - A bridge to use an OJS application and libraries in any
application.
Copyright (C) 2018 David Fernández Aldana

This file is part of OJS Bridge.

OJS Bridge is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

OJS Bridge is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with OJS Bridge. If not, see <http://www.gnu.org/licenses/>.
*/

class OJSBridge {
    private $ojs_base_path;
    // Data saved to be restored from 'end' function
    private $original_path;
    // FIXME: Restoring user functions is not working properly
    //private $original_user_functions;
    private $original_spl_autoload;
    private $is_started = False;

    /**
     * Call this method to get singleton
     *
     * @return OJSBridge
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new OJSBridge();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instantiate it
     *
     */
    private function __construct()
    {

    }

    public function isStarted(){
        return $this->is_started;
    }


    public function doOnce($ojs_base_path, $function){
        $application = $this->startOnce($ojs_base_path);
        $function($application);
        $this->endOnce();
    }

    // Derived from index.php and lib/pkp/classes/Core/PKPApplication.inc.php
    public function startOnce($ojs_base_path){ 
        if (!$this->isStarted()){
            // FIXME: Restoring user functions is not working properly
            //$this->original_user_functions = get_defined_functions()['user'];            
            $this->ojs_base_path = $ojs_base_path;    
            $abs_path = realpath($this->ojs_base_path);
            $this->original_spl_autoload = spl_autoload_functions();
            $this->original_path = getcwd();
            if (function_exists('__')){
                $this->rename_function('__', 'ojs_bridge_translate');
            }
            chdir($abs_path);
            define('INDEX_FILE_LOCATION', "$abs_path/index.php");
            require("./lib/pkp/includes/bootstrap.inc.php");
            $application = Registry::get('application');
            $this->fake_dispatch($application->getDispatcher());
            $this->is_started = True;
            return $application;
        } else {
            throw new Exception('OJS Bridge already started or ended');
        }        
    }    

    // Neccessary to revert the current directory and auto-loading of classes
    public function endOnce($ignoreNotStarted = false){        
        if ($this->isStarted()){
            if (function_exists('ojs_bridge_translate')){
                $this->rename_function('ojs_bridge_translate', '__');
            }
            if (!$this->original_spl_autoload){
                $this->original_spl_autoload = [];
            }            
            foreach (spl_autoload_functions() as $spl_autoload){
                if (!in_array($spl_autoload, $this->original_spl_autoload)){
                    spl_autoload_unregister($spl_autoload);
                }            
            }
            // FIXME: Restoring user functions is not working properly
            /*foreach (get_defined_functions()['user'] as $user_function){
                if (!in_array($user_function, $this->original_user_functions)){
                    $this->rename_function($user_function, "ojs_bridge_$user_function");
                }            
            }*/
            chdir($this->original_path);
            // FIXME: Cannot re-start again as restoring user functions is not working properly.
            // $this->is_started = False;
        } else if (!$ignoreNotStarted){
            throw new Exception('OJS Bridge already ended or not started');
        }        
    }

    // Derived from lib/pkp/classes/Core/Dispatcher.inc.php
    private function fake_dispatch($dispatcher){
        $request = $dispatcher->getApplication()->getRequest();
        AppLocale::initialize($request);
        $routerNames = $dispatcher->getRouterNames();
        if (count($routerNames) > 0){
            foreach($routerNames as $shortcut => $routerCandidateName) {
                $routerCandidate =& $dispatcher->_instantiateRouter($routerCandidateName, $shortcut);
                if ($routerCandidate->supports($request)) {
                    $request->setRouter($routerCandidate);
                    $request->setDispatcher($dispatcher);
                    $router =& $routerCandidate;
                    $dispatcher->_router =& $router;
                    PluginRegistry::loadCategory('generic', true);
                    break;
                }
            }        
        }  		
    }

    // Wrapper to rename function of various libraries (needed for '__' translate function) for Wordpress Compatibility
    private function rename_function($old_name, $new_name){      
        // [tested - recommended] Original PHP < 7, PHP >= 7 -> runkit7 
        if (function_exists('runkit_function_rename')){
            if (function_exists($new_name)){
                runkit_function_rename($new_name, "ojs_bridge_$new_name");
            }
            runkit_function_rename($old_name, $new_name);
        // PHP < 7
        } else if (function_exists('rename_function')){
            if (function_exists($new_name)){
                rename_function($new_name, "ojs_bridge_$new_name");
            }
            rename_function($old_name, $new_name);
        }
    }
}

