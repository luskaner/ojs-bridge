<?php

require_once(dirname(__FILE__) . '/../libraries/ojs-bridge/ojs_bridge.inc.php');

class MainsController extends MvcPublicController {
    private $ojs_bridge;
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        // Modern Strategy
        OJSBridge::Instance()->doOnce(dirname(__FILE__) . '/../../../../../../ojs', function($application){
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
        });

        /*
        // Classic Strategy
        $application = OJSBridge::Instance()->startOnce(dirname(__FILE__) . '/../../../../../../ojs');
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
        
        OJSBridge::Instance()->endOnce();
        */
    }
}

