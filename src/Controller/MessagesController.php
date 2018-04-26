<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 *
 * @method \App\Model\Entity\Message[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MessagesController extends AppController
{

    public $uses = array('Messages', 'Users');

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
//        $this->paginate = [
//            'contain' => ['Senders', 'Recipients']
//        ];
        $messages = $this->paginate($this->Messages);

        $this->set(compact('messages'));
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        //get all messages from this user
        $messages_in_thread = $this->Messages->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('sender' => $id, 'recipient' => $this->Auth->user('id')),
                    array('recipient' => $id, 'sender' => $this->Auth->user('id')),
                )

            )
        ));

        $received_messages = $this->Messages->find('all', array(
            'conditions' => array(
                array('recipient' => $this->Auth->user('id')),
            )
        ));


        $update_query = $received_messages;
        $update_query->update()
            ->set(['seen' => true])
            ->where(['sender' => $id])
            ->execute();


        //sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $this->Auth->user('id');
            $message_data['recipient'] = $id;
            $message_data['sent'] = date('Y-m-d H:i:s');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }

        $this->loadModel('Users');
        $users = $this->Users->find('all', [
            'contain' => ['Interests']
        ]);

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user['id']] = $user;
        }
        $sent_to_id = $id;

        $user = $user_array[$sent_to_id];
        $interests = $user_array[$sent_to_id]->interests;
        $this->loadComponent('Allowed');

        $auth_user = $this->Auth->user();
        $allowed_user = $this->Allowed->checkAllowed($user, $auth_user);

        $this->set(compact('message', 'user_array', 'sent_to_id', 'allowed_user', 'interests'));
    }

    public function instantMessages($id = null)
    {
        //get all messages from this user
        $messages_in_thread = $this->Messages->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('sender' => $id, 'recipient' => $this->Auth->user('id')),
                    array('recipient' => $id, 'sender' => $this->Auth->user('id')),
                )

            )
        ));

        $this->loadComponent('Message');
        $this->Message->sendMessages($this->Auth->user('id'), $id);

        $this->loadModel('Users');
        $users = $this->Users->find()->all();

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user['id']] = $user;
        }
        $sent_to_id = $id;

        $this->set(compact('messages_in_thread', 'sent_to_id'));
    }

    public function connectionMessages($id = null)
    {
        //get all messages from this user
        $messages_in_thread = $this->Messages->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('sender' => $id, 'recipient' => $this->Auth->user('id')),
                    array('recipient' => $id, 'sender' => $this->Auth->user('id')),
                )

            ),
            'order' => array('sent' => 'DESC')
        ))->limit('5');

        $messages_in_thread_array = $messages_in_thread->toArray();
        $messages_in_thread_ordered = array_reverse($messages_in_thread_array);
        //sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $this->Auth->user('id');
            $message_data['recipient'] = $id;
            $message_data['sent'] = date('Y-m-d H:i:s');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }
        $this->loadModel('Users');
        $users = $this->Users->find()->all();

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user['id']] = $user;
        }

        $this->set(compact('messages_in_thread_ordered'));
    }

    public function inbox()
    {
        //find all messages relating to current user
        $messages = $this->Messages->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('sender' => $this->Auth->user('id')),
                    array('recipient' => $this->Auth->user('id')),
                )

            )
        ));

        $this->loadModel('Users');
        $users = $this->Users->find()->all();

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user['id']] = $user;
        }

        //find all users who have either sent or received messages relating to current user
        $messaged = [];
        foreach ($messages as $message) {
            if ($message->sender != $this->Auth->user('id') && !in_array($message->sender, $messaged)) {
                array_push($messaged, $message->sender);
            }
            if ($message->recipient != $this->Auth->user('id') && !in_array($message->recipient, $messaged)) {
                array_push($messaged, $message->recipient);
            }
        }
        //for each user find all related messages
        $message_threads = [];
        foreach ($messaged as $messaged_user) {
            $messages_in_thread = $this->Messages->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        array('sender' => $messaged_user),
                        array('recipient' => $messaged_user),
                    )

                )
            ));
            $message_threads[$messaged_user] = $messages_in_thread;

        }


        $this->set(compact('message_threads', 'user_array'));
    }

    //probably don't need this kid
    public function compose($recipient = null)
    {
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $this->Auth->user('id');
            $message_data['recipient'] = $recipient;
            $message_data['sent'] = date('Y-m-d H:i:s');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
                return $this->redirect(['action' => 'view', $recipient]);
            }
            $this->Flash->error(__('The message could not be sent. Please, try again.'));

        }
        $this->loadModel('Users');
        $recipients = $this->Users->find('list', ['limit' => 200]);
        $this->set(compact('message', 'recipients'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $message = $this->Messages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());
            if ($this->Messages->save($message)) {
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The message could not be saved. Please, try again.'));
        }
//        $senders = $this->Messages->Senders->find('list', ['limit' => 200]);
//        $recipients = $this->Messages->Recipients->find('list', ['limit' => 200]);
        $this->set(compact('message'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $message = $this->Messages->get($id);
        if ($this->Messages->delete($message)) {
        } else {
            $this->Flash->error(__('The message could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function unreadMessages()
    {
        $messages = $this->Messages->find('all', array(
            'conditions' => array(
                array('recipient' => $this->Auth->user('id')),
            )
        ));

        $unread_messages = array();
        foreach ($messages as $message) {
            $seen = $message['seen'];

            if ($seen == false || $seen == null) {
                array_push($unread_messages, $message['id']);
            }
        }

        $notifications = count($unread_messages);
        $this->set(compact('notifications'));
    }
}

