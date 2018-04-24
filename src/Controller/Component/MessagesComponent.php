<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class MessagesComponent extends Component
{

    public function getMessages($message_data, $sender, $recipient)
    {

        //sending messages from within message view


            $message_data = $message_data;
            $message_data['sender'] = $sender;
            $message_data['recipient'] = $recipient;
            $message_data['sent'] = date('Y-m-d h:i');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('Message sent'));
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }
}