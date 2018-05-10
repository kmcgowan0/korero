<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Main component
 */
class EmailComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /*
    * Email Configuration
    * @params : array $config [Cake Email Params]
    *        - $config[to]   - Email of receiver
    *        - $config[from] - Email of sender
    *        - $cofig[message] - Message of email
    *          - $config[subject] - subject of email
    * @return : boolean
    */

    public function sendEmail($config= array()){ //pr($config); exit;
        $defaults = array_merge(array('sendAs'=>'html','transport'=>'gmail','from'=>'test'),$config);
        try{
            $Email = new Email();
            $Email->from(Configure::read('Site.email_from'))
                ->template('themetemplate', 'themelayout')
                ->to($defaults['to'])
                ->subject($defaults['subject'])
                ->emailFormat($defaults['sendAs'])
                ->transport($defaults['transport'])
                ->viewVars(['title' => $config['title'],'content'=>$config['body']]);
            if(isset($defaults['fileurl']) && isset($defaults['filename'])){
                $Email->attachments([
                    $defaults['filename'] => [
                        'file' => $defaults['fileurl']
                    ]
                ]);
            }

            if(!$Email->send())
                return false;


        } catch (\Exception $e) {
            return false;
            //$this->Flash->error($e->getMessage());
        }
        return true;
    }

}