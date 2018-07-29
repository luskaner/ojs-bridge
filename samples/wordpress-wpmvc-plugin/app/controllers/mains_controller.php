<?php

require_once(dirname(__FILE__) . '/../libraries/ojs-bridge/ojs_bridge.inc.php');

class MainsController extends MvcPublicController {
    private $ojs_bridge;
    public function __construct(){
        parent::__construct();
	// OJS is supposed to be installed in same level as wordpress
        $this->ojs_bridge = new OJSBridge(dirname(__FILE__) . '/../../../../../../ojs');
    }

    public function index(){        
        $application = $this->ojs_bridge->start();
        $request = $application->getRequest();

        // DAO
        $journalDao = DAORegistry::getDAO('JournalDAO');
        if (method_exists($journalDao, 'getTitles')){
            // OJS 3
            echo '<h1>journalDao->getTitles</h1>';
            var_dump($journalDao->getTitles());
        } else {
            // OJS 2
            echo '<h1>journalDao->getJournalTitles</h1>';
            var_dump($journalDao->getJournalTitles());
        }
        
        // Specific class
        echo '<h1>PKPLocale->getAllLocales</h1>';
        import('lib.pkp.classes.i18n.PKPLocale');
        $locale = new PKPLocale();
        $locale->initialize($request);
        echo '<pre>';
        var_dump($locale->getAllLocales());
        echo '</pre>';

        $this->ojs_bridge->end();
    }
}

