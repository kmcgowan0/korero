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
//        $this->loadModel('Interests');
        $users = $this->Users->find('all', [
            'contain' => ['Interests']
        ]);

        $user_array = [];
        foreach ($users as $user) {
            $user_array[$user['id']] = $user;
        }
        $sent_to_id = $id;

        $auth_id = $this->Auth->user('id');

        $user = $user_array[$sent_to_id];
        $all_interests = $user_array[$sent_to_id]->interests;
        $my_interests = $user_array[$auth_id]->interests;

        $all_interests_array = array();
        foreach ($all_interests as $an_interest) {
            array_push($all_interests_array, $an_interest->name);
        }

        $my_interests_array = array();
        foreach ($my_interests as $an_interest) {
            array_push($my_interests_array, $an_interest->name);
        }

        $mutual_interest_array = array_intersect($all_interests_array, $my_interests_array);


        $this->loadComponent('Allowed');

        $authorised_user_id = $this->Auth->user('id');
        $authorised_user = $this->Users->get($authorised_user_id, [
            'contain' => ['Interests']
        ]);
        $allowed_user = $this->Allowed->checkAllowed($user, $authorised_user);
        
        $this->loadComponent('Blocked');
       
        $blocked_user = $this->Blocked->blockedUser($user, $authorised_user);
        $blocked_by = $this->Blocked->blockedBy($user, $authorised_user);

        $this->set(compact('message', 'blocked_user', 'blocked_by', 'authorised_user', 'user_array', 'user', 'sent_to_id', 'allowed_user', 'messages_in_thread', 'mutual_interests', 'all_interests_array', 'my_interests', 'mutual_interest_array'));
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
        ));

        $messages_in_thread_array = $messages_in_thread->toArray();
        $messages_in_thread_ordered = array_reverse($messages_in_thread_array);
        $count = count($messages_in_thread_ordered);
        $first_messages = $messages_in_thread_ordered;


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

        $this->set(compact('messages_in_thread_ordered', 'first_messages', 'count', 'remove_messages'));
    }

    public function markRead($id = null) {

        $auth_id = $this->Auth->user('id');
        $messages_in_thread = $this->Messages->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('sender' => $id, 'recipient' => $this->Auth->user('id')),
                    array('recipient' => $id, 'sender' => $this->Auth->user('id')),
                )

            ),
            'order' => array('sent' => 'DESC')
        ));

        $update_query = $messages_in_thread;
        $update_query->update()
            ->set(['seen' => true])
            ->where(['sender' => $id, 'recipient' => $auth_id])
            ->execute();

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
                        array('sender' => $messaged_user, 'recipient' => $this->Auth->user('id')),
                        array('recipient' => $messaged_user, 'sender' => $this->Auth->user('id')),
                    )
                )
            ));
            $message_threads[$messaged_user] = $messages_in_thread;

        }
        $sent_time_sort = array();
        $unique_user_array = array();
        foreach ($message_threads as $id => $message_thread) {
            if (!in_array($id, $unique_user_array)) {
                array_push($unique_user_array, $id);
                foreach ($message_thread as $key => $message) {
                    if ($message['sender'] == $this->Auth->user('id')) {
                        $sent_time_sort[$message['recipient']] = $message['sent'];
                    } else if ($message['recipient'] == $this->Auth->user('id')) {
                        $sent_time_sort[$message['sender']] = $message['sent'];
                    }
                }
            }

        }

        array_multisort($sent_time_sort, SORT_DESC, $message_threads);


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

    public function messagesNotifications()
    {
        $messages = $this->Messages->find('all', array(
            'conditions' => array(
                array('recipient' => $this->Auth->user('id')),
            )
        ));

        $unread_messages = array();
        $senders = array();
        foreach ($messages as $message) {
            $seen = $message['seen'];

            if ($seen == false || $seen == null) {
                array_push($unread_messages, $message['sender']);
            }
        }
        $unread_counts = array_count_values($unread_messages);

        $notifications = count($unread_messages);
        $this->set(compact('notifications', 'unread_messages', 'senders', 'unread_counts'));
    }
}

