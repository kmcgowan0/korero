<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class MessageComponent extends Component
{

    public function sendMessages($sender, $recipient = null)
    {

        //sending messages from within message view
        $this->Messages = TableRegistry::get('Messages');

        //sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $sender;
            if ($recipient != null) {
                $message_data['recipient'] = $recipient;
            }
            $message_data['sent'] = date('Y-m-d h:i');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('Message sent'));
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }
        return $message;

    }
}